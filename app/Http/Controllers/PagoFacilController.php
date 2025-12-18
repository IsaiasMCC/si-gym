<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PagoFacilController extends Controller
{
    private $tokenService = "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d504a62547a9de71bfc76be2c2ae01039ebcb0f74a96f0f1f56542c8b51ef7a2a6da9ea16f23e52ecc4485b69640297a5ec6a701498d2f0e1b4e7f4b7803bf5c2eba";
    private $tokenSecret = "0C351C6679844041AA31AF9C";
    private $baseUrl = "https://masterqr.pagofacil.com.bo/api/services/v2";

    /**
     * Obtener el Bearer Token (se cachea por 3 horas)
     */
    private function getBearerToken()
    {
        return Cache::remember('pagofacil_bearer_token', 3600, function () { // 1 hora de cache

            $response = Http::withHeaders([
                'tcTokenService' => $this->tokenService,
                'tcTokenSecret' => $this->tokenSecret,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/login');

            Log::info("Login PagoFácil", [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error("Error al autenticar con PagoFácil", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("No se pudo autenticar con PagoFácil: " . $response->body());
            }

            $data = $response->json();

            // El token está en values.accessToken según tu respuesta
            $token = $data['values']['accessToken'] ?? null;

            if (!$token) {
                Log::error("Token no encontrado en respuesta", ['data' => $data]);
                throw new \Exception("Token no encontrado en la respuesta de login");
            }

            Log::info("Token obtenido exitosamente", ['token_preview' => substr($token, 0, 20) . '...']);
            return $token;
        });
    }

    public function generarQR(Request $request)
    {
        try {
            $pago   = PlanPago::findOrFail($request->pago_id);
            $subscripcion = $pago->subscripcion;
            $usuario = $subscripcion->usuario;

            $monto = (float) $request->monto;
            // $montoPendiente = $pedido->getSaldoPendienteAttribute();

            // if ($monto <= 0 || $monto > $montoPendiente) {
            //     return response()->json([
            //         'error' => 'Monto inválido'
            //     ], 422);
            // }

            /*
        |--------------------------------------------------------------------------
        | 1️⃣ CREAR REGISTRO PENDIENTE (INTENCIÓN DE PAGO)
        |--------------------------------------------------------------------------
        | Esto NO es el pago final
        */
            $detallePago = Pago::create([
                'fecha_pago'     => now()->format('Y-m-d'),
                'plan_pago_id' => $pago->id,
                'monto'     => $monto,
                'metodo_pago' => 'qr', // PagoFácil
                'referencia' => null,
                'estado'    => 'pendiente'
            ]);

            // Este será el ID que PagoFácil nos devolverá en el callback
            $paymentNumber = 'DP-' . $detallePago->id . '-' . time();

            /*
        |--------------------------------------------------------------------------
        | 2️⃣ ARMAR DETALLE DE ORDEN (solo informativo)
        |--------------------------------------------------------------------------
        */
            $orderDetail = [];
            // foreach ($pedido->detalles as $index => $detalle) {
            //     $orderDetail[] = [
            //         "serial"   => $index + 1,
            //         "product"  => $detalle->producto->nombre,
            //         "quantity" => $detalle->cantidad,
            //         "price"    => (float) $detalle->subtotal,
            //         "discount" => 0,
            //         "total"    => (float) ($detalle->subtotal * $detalle->cantidad)
            //     ];
            // }

            /*
        |--------------------------------------------------------------------------
        | 3️⃣ TOKEN PAGO FÁCIL
        |--------------------------------------------------------------------------
        */
            $bearerToken = $this->getBearerToken();

            // Log::info('PagoFácil callback URL', [
            //     // 'url' => route('pagofacil.callback')
            //     'url' => "http://mail.tecnoweb.org.bo/inf513/grupo06sc/proyecto_2/public/api/pagofacil/callback"
            // ]);

            /*
        |--------------------------------------------------------------------------
        | 4️⃣ GENERAR QR EN PAGOFÁCIL
        |--------------------------------------------------------------------------
        */
            $resp = Http::timeout(30)
                ->withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $bearerToken
                ])
                ->post($this->baseUrl . '/generate-qr', [
                    "paymentMethod" => 4,
                    "clientName"    => $usuario->nombres . " " . $usuario->apellidos ?? "Cliente",
                    "documentType"  => 1,
                    "documentId"    => $usuario->ci ?? "0",
                    "phoneNumber"   => "77046105",
                    "email"         => $usuario->email,
                    "paymentNumber" => $paymentNumber, // 🔑 CLAVE
                    "amount"        => $monto,
                    "currency"      => 2,
                    "clientCode"    => "11001",
                    // "callbackUrl"   => route('pagofacil.callback'),
                    "callbackUrl"   => "http://mail.tecnoweb.org.bo/inf513/grupo18sc/proyecto2/sis-gym/public/api/pagofacil/callback",
                    "orderDetail"   => $orderDetail
                ]);

            if (!$resp->successful()) {
                Log::error('Error PagoFácil generate-qr', [
                    'status' => $resp->status(),
                    'body'   => $resp->body()
                ]);

                // rollback de la intención si falla el QR
                $detallePago->delete();

                return response()->json([
                    'error' => 'Error al generar QR'
                ], 500);
            }

            $data = $resp->json();

            Log::info('Respuesta generate-qr', $data);

            $transactionId = $data['values']['transactionId'] ?? null;
            $qrBase64      = $data['values']['qrBase64'] ?? null;

            if (!$transactionId || !$qrBase64) {
                $detallePago->delete();

                return response()->json([
                    'error' => 'Respuesta incompleta de PagoFácil',
                    'data'  => $data
                ], 500);
            }

            /*
        |--------------------------------------------------------------------------
        | 5️⃣ GUARDAR DATOS DE PAGOFÁCIL (OPCIONAL)
        |--------------------------------------------------------------------------
        */
            $detallePago->update([
                'referencia' => $paymentNumber
            ]);

            return response()->json([
                'qr'           => 'data:image/png;base64,' . $qrBase64,
                'payment_id'   => $detallePago->id,
                'payment_code' => $paymentNumber,
                'transaccion'  => $transactionId
            ]);
        } catch (\Throwable $e) {

            Log::error('Excepción generarQR', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function consultarEstado(Request $request)
    {
        try {
            $bearerToken = $this->getBearerToken();

            Log::info("Consultando estado - Request", [
                'transactionId' => $request->tnTransaccion,
                'url' => $this->baseUrl . '/query-transaction'
            ]);

            // Es POST y usa pagofacilTransactionId en el body
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/query-transaction', [
                'pagofacilTransactionId' => $request->tnTransaccion
            ]);

            Log::info("Respuesta consulta estado", [
                'status' => $resp->status(),
                'body' => $resp->json()
            ]);

            if (!$resp->successful()) {
                return response()->json([
                    "error" => "Error al consultar estado",
                    "status" => $resp->status(),
                    "details" => $resp->json()
                ]);
            }

            $data = $resp->json();

            // El estado viene en values.paymentStatus
            $paymentStatus = $data['values']['paymentStatus'] ?? null;

            // Mapear el ID del estado a texto legible
            $estadoTexto = "DESCONOCIDO";
            switch ($paymentStatus) {
                case 1:
                    $estadoTexto = "PENDIENTE";
                    break;
                case 2:
                    $estadoTexto = "COMPLETADO";
                    break;
                case 3:
                    $estadoTexto = "RECHAZADO";
                    break;
                case 4:
                    $estadoTexto = "ANULADO";
                    break;
            }

            return response()->json([
                "estado" => $estadoTexto,
                "paymentStatus" => $paymentStatus,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            Log::error("Excepción consultarEstado", [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                "error" => "Error: " . $e->getMessage()
            ], 500);
        }
    }

    public function urlCallback(Request $request)
    {
        Log::info('Callback PagoFácil recibido', $request->all());

        try {
            $paymentNumber = $request->input('PedidoID');
            $estado        = (int) $request->input('Estado');
            $fecha         = $request->input('Fecha');
            $hora          = $request->input('Hora');
            $metodoPago    = $request->input('MetodoPago');

            if (!$paymentNumber) {
                Log::warning('Callback sin PedidoID');
                return response()->json(['status' => 1], 200);
            }

            /*
        |--------------------------------------------------------------------------
        | 1️⃣ BUSCAR INTENCIÓN DE PAGO
        |--------------------------------------------------------------------------
        */
            $detallePago = Pago::where('referencia', $paymentNumber)->first();

            if (!$detallePago) {
                Log::warning('DetallePago no encontrado', [
                    'paymentNumber' => $paymentNumber
                ]);
                return response()->json(['status' => 1], 200);
            }

            /*
        |--------------------------------------------------------------------------
        | 2️⃣ IDEMPOTENCIA REAL
        |--------------------------------------------------------------------------
        */
            if ($detallePago->estado === 'pagado') {
                Log::info('Callback duplicado ignorado', [
                    'detalle_pago_id' => $detallePago->id
                ]);
                return response()->json(['status' => 1], 200);
            }

            /*
        |--------------------------------------------------------------------------
        | 3️⃣ SOLO ESTADO 2 CONFIRMA
        |--------------------------------------------------------------------------
        */
            if ($estado !== 2) {
                Log::info('Estado no exitoso', [
                    'estado' => $estado,
                    'detalle_pago_id' => $detallePago->id
                ]);
                return response()->json(['status' => 1], 200);
            }

            /*
        |--------------------------------------------------------------------------
        | 4️⃣ CONFIRMAR PAGO
        |--------------------------------------------------------------------------
        */
            DB::transaction(function () use ($detallePago, $fecha) {

                $detallePago->estado       = 'pagado';
                $detallePago->fecha_pago  = $fecha ?? now()->format('Y-m-d');

                // 🔄 actualizar saldo del pedido si corresponde
                $planPago = $detallePago->planPago;
                $planPago->saldo = 0;
                $planPago->estado = 'pagado';
                $detallePago->save();
                $planPago->save();

            });

            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => 'OK',
                "values" => true
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error callback PagoFácil', [
                'error' => $e->getMessage()
            ]);

            // ⚠️ SIEMPRE 200
            return response()->json([
                'error' => 1,
                'status' => 1,
                'message' => 'OK'
            ], 200);
        }
    }
}

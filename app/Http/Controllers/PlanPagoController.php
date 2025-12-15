<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PlanPago;
use App\Models\Subscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PlanPagoController extends Controller
{
    public function create(Subscripcion $subscripcion)
    {
        return Inertia::render('PlanPagos/Create', [
            'subscripcion' => $subscripcion->load('membresia', 'paquete'),
        ]);
    }

    public function store(Subscripcion $subscripcion, Request $request)
    {
        $validated = $request->validate([
            'tipo_pago' => 'required|in:contado,credito',
            'cuotas' => 'nullable|integer|min:1', // solo para crédito
        ]);

        $monto_total = $subscripcion->membresia->precio_base + ($subscripcion->paquete?->precio_adicional ?? 0);

        if ($validated['tipo_pago'] === 'contado') {
            PlanPago::create([
                'tipo_pago' => 'contado',
                'suscripcion_id' => $subscripcion->id,
                'fecha' => now(),
                'fecha_vencimiento' => now(),
                'monto' => $monto_total,
                'saldo' => 0,
                'estado' => 'pagado',
            ]);
        } else {
            $cuotas = $validated['cuotas'] ?? 1;
            $monto_cuota = round($monto_total / $cuotas, 2);
            $fecha = now();
            for ($i = 0; $i < $cuotas; $i++) {
                PlanPago::create([
                    'tipo_pago' => 'credito',
                    'suscripcion_id' => $subscripcion->id,
                    'fecha' => $fecha,
                    'fecha_vencimiento' => $fecha->copy()->addMonth($i + 1),
                    'monto' => $monto_cuota,
                    'saldo' => $monto_cuota,
                    'estado' => 'pendiente',
                ]);
            }
        }

        return Redirect::route('subscripciones.index')->with('success', 'Plan de pago generado correctamente.');
    }

    public function pagar(Request $request, PlanPago $planPago)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,qr',
            'referencia' => 'nullable|string|max:100'
        ]);

        // Si ya está pagado, no hacemos nada
        if ($planPago->estado === 'pagado') {
            return back()->with('error', 'Este plan ya está pagado.');
        }

        // Crear pago
        $pago = Pago::create([
            'plan_pago_id' => $planPago->id,
            'monto' => $planPago->saldo > 0 ? $planPago->saldo : $planPago->monto,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago' => now(),
            'estado' => 'pagado',
            'referencia' => $request->referencia,
        ]);

        // Actualizar plan de pago
        $planPago->update([
            'saldo' => 0,
            'estado' => 'pagado',
        ]);

        return back()->with('success', 'Pago realizado correctamente.');
    }
}

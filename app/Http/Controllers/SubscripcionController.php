<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Paquete;
use App\Models\PlanPago;
use App\Models\Subscripcion;
use App\Models\SubscripcionPaquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SubscripcionController extends Controller
{

    public function catalogo()
    {
        return Inertia::render('Subscripciones/Index', [
            'membresias' => Membresia::where('estado', 'activo')->get()
        ]);
    }

    public function paquetes_adicional($id)
    {
        $membresia = Membresia::findOrFail($id);
        $paquetes = Paquete::where('estado', 'activo')
            ->where('membresia_id', $id)
            ->get();

        return Inertia::render('Subscripciones/Paquetes', [
            'membresia' => $membresia,
            'paquetes' => $paquetes,
        ]);
    }

    public function resumen(Request $request, Membresia $membresia)
    {
        $paqueteIds = $request->input('paquetes', []);
        $paquetes = Paquete::whereIn('id', $paqueteIds)->get();

        $total = $membresia->precio_base + $paquetes->sum('precio_adicional');

        return Inertia::render('Subscripciones/Resumen', [
            'membresia' => $membresia,
            'paquetes' => $paquetes,
            'total' => $total
        ]);
    }

    public function confirmar(Request $request, $membresiaId)
    {
        $request->validate([
            'paquetes' => 'array',
            'paquetes.*' => 'exists:paquetes,id',
            'tipo_pago' => 'required|in:contado,credito',
        ]);

        $user = Auth::user();
        $membresia = Membresia::findOrFail($membresiaId);
        // Crear subscripción
        $subscripcion = Subscripcion::create([
            'user_id' => $user->id,
            'membresia_id' => $membresiaId,
            'paquete_id' => null, // Podríamos guardar uno principal si quieres
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addDays($membresia->duracion_dias), // Por ejemplo, 30 días
            'estado' => 'activa'
        ]);

        // Calcular monto total
        $montoBase = $subscripcion->membresia->precio_base;
        $montoPaquetes = 0;
        $paquetes = [];
        if ($request->paquetes) {
            $paquetes = Paquete::whereIn('id', $request->paquetes)->get();
            $montoPaquetes = $paquetes->sum('precio_adicional');
        }
        $total = $montoBase + $montoPaquetes;
        foreach ($paquetes as $paquete) {
            SubscripcionPaquete::create([
                'subscripcion_id' => $subscripcion->id,
                'paquete_id' => $paquete->id,
            ]);
        }
        // Crear plan de pago
        if ($request->tipo_pago === 'contado') {
            PlanPago::create([
                'suscripcion_id' => $subscripcion->id,
                'tipo_pago' => 'contado',
                'fecha' => now(),
                'fecha_vencimiento' => now(),
                'monto' => $total,
                'saldo' => 0,
                'estado' => 'pendiente'
            ]);
        } else {
            // Crédito, ejemplo 3 cuotas iguales
            $numCuotas = 3;
            $montoCuota = round($total / $numCuotas, 2);
            for ($i = 0; $i < $numCuotas; $i++) {
                PlanPago::create([
                    'suscripcion_id' => $subscripcion->id,
                    'tipo_pago' => 'credito',
                    'fecha' => now()->addDays(30 * $i),
                    'fecha_vencimiento' => now()->addDays(30 * ($i + 1)),
                    'monto' => $montoCuota,
                    'saldo' => $montoCuota,
                    'estado' => 'pendiente'
                ]);
            }
        }

        return redirect()->route('subscripciones.index')->with('success', 'Subscripción creada correctamente.');
    }

    public function index()
    {
        $user = Auth::user();

        // Traemos subscripciones con membresía y paquetes
        $subscripciones = Subscripcion::with(['membresia', 'paquetes.paquete'])
            ->where('user_id', $user->id)
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'membresia' => [
                        'nombre' => $s->membresia->nombre,
                        'descripcion' => $s->membresia->descripcion,
                        'precio_base' => $s->membresia->precio_base,
                    ],
                    'paquetes' => $s->paquetes->map(function ($p) {
                        return [
                            'id' => $p->paquete->id,
                            'nombre' => $p->paquete->nombre,
                            'descripcion' => $p->paquete->descripcion,
                            'precio_adicional' => $p->paquete->precio_adicional,
                        ];
                    }),
                    'fecha_inicio' => $s->fecha_inicio,
                    'fecha_fin' => $s->fecha_fin,
                    'estado' => $s->estado,
                ];
            });

        return Inertia::render('Subscripciones/Subscripciones', [
            'subscripciones' => $subscripciones
        ]);
    }

    public function create()
    {
        return Inertia::render('Subscripciones/Create', [
            'membresias' => Membresia::where('estado', 'activo')->get(),
            'paquetes' => Paquete::where('estado', 'activo')->get(),
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'membresia_id' => 'required|exists:membresias,id',
            'paquete_id' => 'nullable|exists:paquetes,id',
            'fecha_inicio' => 'required|date',
        ]);

        $membresia = Membresia::findOrFail($validated['membresia_id']);
        $validated['fecha_fin'] = date('Y-m-d', strtotime($validated['fecha_inicio'] . ' +' . $membresia->duracion_dias . ' days'));
        $validated['estado'] = 'activa';

        $subscripcion = Subscripcion::create($validated);

        return Redirect::route('plan_pagos.create', $subscripcion->id);
    }

    public function show($id)
    {
        $auth =  Auth::user();
        $subscripcion = Subscripcion::where('user_id', $auth->id)
            ->with(['membresia', 'paquetes.paquete', 'planPagos'])
            ->findOrFail($id);

        return Inertia::render('Subscripciones/Pagos', [
            'subscripcion' => $subscripcion
        ]);
    }
}

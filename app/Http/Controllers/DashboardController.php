<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PlanPago;
use App\Models\Reserva;
use App\Models\Subscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'users'   => User::count(),
                'sales'   => PlanPago::sum('monto') ?? 0,
                'subscripcions'  => Subscripcion::count(),
                'pending' => Pago::where('estado', 'pendiente')->count(),
            ],
            'subscripcions' => Subscripcion::with('membresia')->latest()
                ->take(10)
                ->get(),

        ]);
    }


    public function index_search(Request $request)
    {
        $q = $request->input('q');

        // Buscar en usuarios
        $users = User::where('nombres', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->get();

        // Buscar en subscripciones
        $subscripciones = Subscripcion::where('id', 'like', "%{$q}%")->with('usuario', 'membresia')
            ->orWhere('estado', 'like', "%{$q}%")
            ->get();

        // Buscar en planes de pago
        $planes = PlanPago::where('estado', 'like', "%{$q}%")->with('subscripcion.membresia', 'subscripcion.usuario')->get();

        return Inertia::render('Search/Result', [
            'query' => $q,
            'users' => $users,
            'subscripciones' => $subscripciones,
            'planes' => $planes,
        ]);
    }
}

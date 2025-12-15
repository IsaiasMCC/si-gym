<?php

namespace App\Http\Controllers;

use App\Models\PlanPago;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            // 'stats' => [
            //     'users'   => User::count(),
            //     'sales'   => PlanPago::sum('total') ?? 0,
            //     'orders'  => Reserva::count(),
            //     'pending' => Reserva::where('estado_reserva', 'PENDIENTE')->count(),
            // ],
            // 'reservas' => Reserva::latest()
            //     ->take(10)
            //     ->get([
            //         'id',
            //         'fecha',
            //         'hora',
            //         'total',
            //         'estado_reserva',
            //     ]),

        ]);
    }


    public function index_search(Request $request)
    {
        $q = $request->input('q');

        // Buscar en usuarios
        $users = User::where('nombres', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->get();

        // Buscar en reservas
        // $reservas = Reserva::where('id', 'like', "%{$q}%")
        //     ->orWhere('estado_reserva', 'like', "%{$q}%")
        //     ->get();

        // Buscar en ventas (planes de pago)
        $ventas = PlanPago::where('total', 'like', "%{$q}%")->get();

        return Inertia::render('Search/Result', [
            'query' => $q,
            'users' => $users,
            // 'reservas' => $reservas,
            'ventas' => $ventas,
        ]);
    }
}

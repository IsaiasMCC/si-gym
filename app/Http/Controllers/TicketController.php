<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{

    // Método para mostrar el detalle de la reserva con tickets
    public function detalle(Reserva $reserva)
    {
        // Cargar detalles de la reserva + trabajador de cada detalle
        $reserva->load([
            'detalles.servicio',
            'detalles.trabajador',
            'tickets.mensajes.empleado'
        ]);

        return Inertia::render('Reservas/Cliente/Show', [
            'reserva' => $reserva,
            'userId' => Auth::id(), // id del cliente actual
        ]);
    }

    // Crear ticket
    public function store(Request $request)
    {
        $request->validate([
            'detalles' => 'required|string',
            'reserva_id' => 'required|exists:reservas,id',
        ]);

        $ultimoNro = Ticket::max('nro') ?? 0;

        $ticket = Ticket::create([
            'nro' => $ultimoNro + 1,
            'detalles' => $request->detalles,
            'estado_ticket' => 'PENDIENTE',
            'reserva_id' => $request->reserva_id,
        ]);

        return back()->with('nuevoTicket', $ticket);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PresupuestoServicio;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('search');

        $reservas = Reserva::with('cliente')
            ->when($filters['search'] ?? false, function ($query, $search) {
                $query->where('estado_reserva', 'like', "%{$search}%")
                    ->orWhereHas('cliente', function ($q) use ($search) {
                        $q->where('nombres', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%");
                    });
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Reservas/Index', [
            'reservas' => $reservas,
            'filters' => $filters,
        ]);
    }

    //CLIENTE
    public function misReservas(Request $request)
    {
        $filters = $request->only('search');

        $reservas = Reserva::where('cliente_id', Auth::id())
            ->when($filters['search'] ?? false, function ($query, $search) {
                $query->where('estado_reserva', 'like', "%{$search}%");
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Reservas/MisReservas', [
            'reservas' => $reservas,
            'filters' => $filters,
        ]);
    }

    //TRABAJADOR
    public function misReservasEmpleado(Request $request)
    {
        $filters = $request->only('search');

        $reservas = Reserva::whereHas('detalles', function ($query) {
            // Filtramos solo los detalles donde el trabajador es el actual
            $query->where('trabajador_id', Auth::id());
        })
            ->when($filters['search'] ?? false, function ($query, $search) {
                $query->where('estado_reserva', 'like', "%{$search}%");
            })
            ->with('detalles.trabajador') // cargamos los detalles y su trabajador
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Reservas/Trabajador/Index', [
            'reservas' => $reservas,
            'filters' => $filters,
        ]);
    }


    public function create(Request $request)
    {
        $servicios = collect(json_decode($request->input('servicios', '[]'), true));
        $total = $request->input('total', 0);

        return Inertia::render('Reservas/Create', [
            'servicios' => $servicios,
            'total' => $total,
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*.detalle' => 'required|string',
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.precio' => 'required|numeric',
        ]);


        $reserva = Reserva::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'detalles' => 'Cotización de servicios',
            'total' => $request->total,
            'estado_reserva' => "PENDIENTE",
            'cliente_id' => Auth::id(),
        ]);


        foreach ($request->servicios as $servicioData) {
            PresupuestoServicio::create([
                'detalles' => $servicioData['detalle'],
                'precio' => $servicioData['precio'],
                'servicio_id' => $servicioData['servicio_id'],
                'reserva_id' => $reserva->id,
                'trabajador_id' => null,
            ]);
        }

        return redirect()->route('reservas.mis-reservas')
            ->with('success', 'Cotización creada correctamente.');
    }



    public function edit($id)
    {
        $reserva = Reserva::with('detalles.servicio', 'detalles.trabajador')->findOrFail($id);

        $trabajadores = User::where('role_id', '2')->get();

        return Inertia::render('Reservas/Edit', [
            'reserva' => $reserva,
            'trabajadores' => $trabajadores,
        ]);
    }

    //CLIENTE
    public function misReservasDetalles($id)
    {
        $reserva = Reserva::with([
            'detalles.servicio',
            'detalles.trabajador',
            'tickets.mensajes.empleado', // <-- carga los tickets con sus mensajes y el empleado que los envió
            'plan_pago'
        ])->findOrFail($id);

        $trabajadores = User::all();
        // donde quieras filtrar solo trabajadores: ->where('rol', 'trabajador')->get();

        return Inertia::render('Reservas/Cliente/Show', [
            'reserva' => $reserva,
            'trabajadores' => $trabajadores,
            'userId' => Auth::id(), // id del cliente actual
        ]);
    }


    //TRABAJADOR
    public function misReservasDetallesEmpleado($id)
    {
        $reserva = Reserva::with(['detalles' => function ($query) {
            $query->where('trabajador_id', Auth::id())
                ->with(['servicio', 'trabajador', 'avances']);
        }])->findOrFail($id);

        $trabajadores = User::all();

        return Inertia::render('Reservas/Trabajador/Show', [
            'reserva' => $reserva,
            'trabajadores' => $trabajadores,
        ]);
    }




    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);

        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*.detalle' => 'required|string',
            'servicios.*.precio' => 'required|numeric|min:0',
            'servicios.*.trabajador_id' => 'required|exists:users,id',
        ]);

        // Actualizamos la reserva
        $reserva->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'estado_reserva' => $request->estado_reserva,
            'total' => collect($request->servicios)->sum('precio'),
        ]);

        // Actualizamos cada detalle
        foreach ($request->servicios as $s) {
            $detalle = PresupuestoServicio::findOrFail($s['id']);
            $detalle->update([
                'detalles' => $s['detalle'],
                'precio' => $s['precio'],
                'trabajador_id' => $s['trabajador_id'],
            ]);
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }




    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return Redirect::back()->with('success', 'Reserva eliminada correctamente.');
    }

    public function catalogo(Request $request)
    {
        $search = $request->input('search');

        // Consultamos solo servicios activos y aplicamos búsqueda
        $servicios = Servicio::query()
            ->where('estado', true)
            ->when($search, fn($q) => $q->where('nombre', 'like', "%{$search}%"))
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Reservas/Catalogo', [
            'servicios' => $servicios,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }
}

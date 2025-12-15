<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class MetodoPagoController extends Controller
{
    public function index()
    {
        return inertia('MetodoPagos/Index', [
            'filters' => request()->all('search', 'estado'),
            'metodos' => MetodoPago::query()
                ->when(request('search'), fn($q, $search) => 
                    $q->where('nombre', 'like', "%{$search}%")
                )
                ->when(request('estado'), fn($q, $estado) => 
                    $q->where('estado', $estado)
                )
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($metodo) => [
                    'id' => $metodo->id,
                    'nombre' => $metodo->nombre,
                    'estado' => $metodo->estado ? 'Activo' : 'Inactivo',
                ]),
        ]);
    }

    public function create()
    {
        return inertia('MetodoPagos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'max:50'],
            'estado' => ['required', Rule::in([true, false])],
        ]);

        MetodoPago::create($validated);

        return Redirect::route('metodo-pagos.index')->with('success', 'Método de Pago creado correctamente.');
    }

    public function edit(MetodoPago $metodoPago)
    {
        return inertia('MetodoPagos/Edit', [
            'metodo' => $metodoPago,
        ]);
    }

    public function update(Request $request, MetodoPago $metodoPago)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'max:50'],
            'estado' => ['required', Rule::in([true, false])],
        ]);

        $metodoPago->update($validated);

        return Redirect::route('metodo-pagos.index')->with('success', 'Método de Pago actualizado correctamente.');
    }

    public function destroy(MetodoPago $metodoPago)
    {
        $metodoPago->delete();

        return Redirect::route('metodo-pagos.index')->with('success', 'Método de Pago eliminado correctamente.');
    }
}

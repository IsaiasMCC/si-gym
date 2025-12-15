<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ServicioController extends Controller
{
    public function index()
    {
        return Inertia::render('Servicios/Index', [
            'filters' => Request()->all('search', 'estado'),
            'servicios' => Servicio::query()
                ->when(Request()->get('search'), function ($q, $search) {
                    $q->where('nombre', 'like', "%{$search}%");
                })
                ->when(Request()->get('estado'), function ($q, $estado) {
                    $q->where('estado', $estado === 'true');
                })
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($servicio) => [
                    'id' => $servicio->id,
                    'nombre' => $servicio->nombre,
                    'detalles' => $servicio->detalles,
                    'precio' => $servicio->precio,
                    'estado' => $servicio->estado,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Servicios/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'max:100'],
            'detalles' => ['nullable', 'max:500'],
            'precio' => ['required', 'numeric'],
            'estado' => ['required', Rule::in([true, false])],
        ]);

        Servicio::create($validated);

        return Redirect::route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        return Inertia::render('Servicios/Edit', [
            'servicio' => $servicio,
        ]);
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'max:100'],
            'detalles' => ['nullable', 'max:500'],
            'precio' => ['required', 'numeric'],
            'estado' => ['required', Rule::in([true, false])],
        ]);

        $servicio->update($validated);

        return Redirect::back()->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return Redirect::route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }
}

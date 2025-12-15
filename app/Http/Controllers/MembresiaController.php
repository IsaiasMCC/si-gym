<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MembresiaController extends Controller
{
    public function index()
    {
        return Inertia::render('Membresias/Index', [
            'filters' => request()->all('search'),
            'membresias' => Membresia::when(
                request('search'),
                fn($q, $search) => $q->where('nombre', 'like', "%{$search}%")
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($m) => [
                'id' => $m->id,
                'nombre' => $m->nombre,
                'duracion_dias' => $m->duracion_dias,
                'precio_base' => $m->precio_base,
                'descripcion' => $m->descripcion,
                'estado' => $m->estado,
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Membresias/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'duracion_dias' => 'required|integer|min:1',
            'precio_base' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Membresia::create($validated);

        return Redirect::route('membresias.index')->with('success', 'Membresía creada correctamente.');
    }

    public function edit(Membresia $membresia)
    {
        return Inertia::render('Membresias/Edit', [
            'membresia' => $membresia
        ]);
    }

    public function update(Membresia $membresia, Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'duracion_dias' => 'required|integer|min:1',
            'precio_base' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $membresia->update($validated);

        return Redirect::route('membresias.index')->with('success', 'Membresía actualizada correctamente.');
    }

    public function destroy(Membresia $membresia)
    {
        $membresia->delete();
        return Redirect::route('membresias.index')->with('success', 'Membresía eliminada correctamente.');
    }
}

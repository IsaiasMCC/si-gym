<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Membresia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaqueteController extends Controller
{
    public function index()
    {
        return Inertia::render('Paquetes/Index', [
            'filters' => request()->all('search'),
            'paquetes' => Paquete::with('membresia')
                ->when(request('search'), fn($q, $search) => $q->where('nombre', 'like', "%{$search}%"))
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($p) => [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'descripcion' => $p->descripcion,
                    'precio_adicional' => $p->precio_adicional,
                    'estado' => $p->estado,
                    'membresia' => $p->membresia ? ['id' => $p->membresia->id, 'nombre' => $p->membresia->nombre] : null,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Paquetes/Create', [
            'membresias' => Membresia::select('id', 'nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'membresia_id' => 'required|exists:membresias,id',
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|string',
            'precio_adicional' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Paquete::create($validated);

        return Redirect::route('paquetes.index')->with('success', 'Paquete creado correctamente.');
    }

    public function edit(Paquete $paquete)
    {
        return Inertia::render('Paquetes/Edit', [
            'paquete' => $paquete,
            'membresias' => Membresia::select('id', 'nombre')->get(),
        ]);
    }

    public function update(Paquete $paquete, Request $request)
    {
        $validated = $request->validate([
            'membresia_id' => 'required|exists:membresias,id',
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|string',
            'precio_adicional' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $paquete->update($validated);

        return Redirect::route('paquetes.index')->with('success', 'Paquete actualizado correctamente.');
    }

    public function destroy(Paquete $paquete)
    {
        $paquete->delete();
        return Redirect::route('paquetes.index')->with('success', 'Paquete eliminado correctamente.');
    }
}

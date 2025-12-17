<?php

namespace App\Http\Controllers;

use App\Models\Rutina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RutinaController extends Controller
{
    public function index()
    {
        return Inertia::render('Rutinas/Index', [
            'filters' => request()->all('search'),
            'rutinas' => Rutina::with('entrenador')
                ->when(request('search'), fn($q, $search) => $q->where('nombre', 'like', "%{$search}%"))
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($r) => [
                    'id' => $r->id,
                    'nombre' => $r->nombre,
                    'descripcion' => $r->descripcion,
                    'nivel' => $r->nivel,
                    'objetivo' => $r->objetivo,
                    'entrenador' => $r->entrenador ? ['id' => $r->entrenador->id, 'nombres' => $r->entrenador->nombres, 'apellidos' => $r->entrenador->apellidos] : null,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Rutinas/Create', [
            'entrenadores' => User::where('role_id', 2)
                ->select('id', 'nombres', 'apellidos')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|string',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
            'objetivo' => 'required|max:255',
            'entrenador_id' => 'required|exists:users,id',
        ]);

        Rutina::create($validated);

        return Redirect::route('rutinas.index')->with('success', 'Rutina creada correctamente.');
    }

    public function edit(Rutina $rutina)
    {
        return Inertia::render('Rutinas/Edit', [
            'rutina' => $rutina,
            'entrenadores' => User::select('id', 'nombres', 'apellidos')->get(),
        ]);
    }

    public function update(Rutina $rutina, Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|string',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
            'objetivo' => 'required|max:255',
            'entrenador_id' => 'required|exists:users,id',
        ]);

        $rutina->update($validated);

        return Redirect::route('rutinas.index')->with('success', 'Rutina actualizada correctamente.');
    }

    public function destroy(Rutina $rutina)
    {
        $rutina->delete();
        return Redirect::route('rutinas.index')->with('success', 'Rutina eliminada correctamente.');
    }
}

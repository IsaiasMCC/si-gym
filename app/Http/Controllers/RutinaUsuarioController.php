<?php

namespace App\Http\Controllers;

use App\Models\RutinaUsuario;
use App\Models\User;
use App\Models\Rutina;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RutinaUsuarioController extends Controller
{
    public function index()
    {
        return Inertia::render('RutinasUsuarios/Index', [
            'rutinaUsuarios' => RutinaUsuario::with(['usuario', 'rutina.entrenador'])
                ->orderByDesc('id')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('RutinasUsuarios/Create', [
            'usuarios' => User::orderBy('nombres')->get(),
            'rutinas' => Rutina::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rutina_id' => 'required|exists:rutinas,id',
            'fecha_asignacion' => 'required|date',
        ]);

        RutinaUsuario::create([
            'user_id' => $request->user_id,
            'rutina_id' => $request->rutina_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'estado' => 'activa',
        ]);

        return redirect()->route('rutinas-usuarios.index');
    }

    public function edit(RutinaUsuario $rutinasUsuario)
    {
        // dd($rutinasUsuario->estado);
        return Inertia::render('RutinasUsuarios/Edit', [
            'rutinaUsuario' => $rutinasUsuario->load(['usuario', 'rutina']),
            'usuarios' => User::orderBy('nombres')->get(),
            'rutinas' => Rutina::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, RutinaUsuario $rutinasUsuario)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rutina_id' => 'required|exists:rutinas,id',
            // 'fecha_asignacion' => 'required|date',
            'estado' => 'required|in:activa,finalizada',
        ]);

        $rutinasUsuario->update($request->all());

        return redirect()->route('rutinas-usuarios.index');
    }

    public function destroy(RutinaUsuario $rutinasUsuario)
    {
        $rutinasUsuario->delete();
        return redirect()->route('rutinas-usuarios.index');
    }
}

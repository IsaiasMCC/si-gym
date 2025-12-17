<?php

namespace App\Http\Controllers;

use App\Models\RutinaUsuario;
use App\Models\User;
use App\Models\Rutina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RutinaUsuarioController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        return Inertia::render('RutinasUsuarios/Index', [
            'rutinaUsuarios' => RutinaUsuario::where('instructor_id', $id)->with(['cliente', 'instructor', 'rutina.entrenador'])
                ->orderBy('id')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        $id = Auth::user()->id;
        // $rutinasEntrenador = Rutina::all();
        $rutinasEntrenador = Rutina::where('entrenador_id', $id)->get();
        $clientes = User::where('role_id', 3)->orderBy('nombres')->get();
        return Inertia::render('RutinasUsuarios/Create', [
            'clientes' => $clientes,
            'rutinas' => $rutinasEntrenador,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rutina_id' => 'required|exists:rutinas,id',
            'fecha_asignacion' => 'required|date',
        ]);
        $id = Auth::user()->id;
        RutinaUsuario::create([
            'cliente_id' => $request->user_id,
            'instructor_id' => $id,
            'rutina_id' => $request->rutina_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'estado' => 'activa',
        ]);

        return redirect()->route('rutinas-usuarios.index');
    }

    public function edit(RutinaUsuario $rutinasUsuario)
    {
        // dd($rutinasUsuario->estado);
        $id = Auth::user()->id;
        // $rutinasEntrenador = Rutina::all();
        $clientes = User::where('role_id', 3)->orderBy('nombres')->get();
        $rutinasEntrenador = Rutina::where('entrenador_id', $id)->get();
        return Inertia::render('RutinasUsuarios/Edit', [
            'rutinaUsuario' => $rutinasUsuario->load(['cliente', 'instructor', 'rutina']),
            'clientes' => $clientes,
            'rutinas' => $rutinasEntrenador,
        ]);
    }

    public function update(Request $request, RutinaUsuario $rutinasUsuario)
    {
        // dd($request->all());
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
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

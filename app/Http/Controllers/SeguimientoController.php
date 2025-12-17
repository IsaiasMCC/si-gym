<?php

namespace App\Http\Controllers;

use App\Models\RutinaUsuario;
use App\Models\Seguimiento;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeguimientoController extends Controller
{
    public function index($id)
    {
        return Inertia::render('Seguimientos/Index', [
            'seguimientos' => Seguimiento::with('usuario')
                ->orderByDesc('fecha_registro')
                ->paginate(10),
            'id' => $id,
        ]);
    }

    public function create($id)
    {
        $rutinaUsuario = RutinaUsuario::findOrFail($id)->load('cliente', 'rutina');
        return Inertia::render('Seguimientos/Create', [
            'usuarios' => User::orderBy('nombres')->get(),
            'rutinaUsuario' => $rutinaUsuario,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rutina_usuario_id' => 'required|exists:rutina_usuarios,id',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'fecha_registro' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $imc = null;
        if ($request->peso && $request->altura) {
            // altura en metros
            $imc = round($request->peso / ($request->altura ** 2), 2);
        }

        Seguimiento::create([
            'rutina_usuario_id' => $request->rutina_usuario_id,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'imc' => $imc,
            'porcentaje_grasa' => null, // se puede calcular o dejar nulo
            'observaciones' => $request->observaciones,
            'fecha_registro' => $request->fecha_registro,
        ]);

        return redirect()->route('seguimientos.index', $request->rutina_usuario_id);
    }


    public function edit(Seguimiento $seguimiento)
    {
         $rutinaUsuario = RutinaUsuario::findOrFail($seguimiento->rutina_usuario_id)->load('cliente', 'rutina');
        return Inertia::render('Seguimientos/Edit', [
            'seguimiento' => $seguimiento,
            'rutinaUsuario' => $rutinaUsuario,
        ]);
    }

    public function update(Request $request, Seguimiento $seguimiento)
    {
        $request->validate([
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'fecha_registro' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $seguimiento->update([
            'peso' => $request->peso,
            'altura' => $request->altura,
            'imc' => $request->peso && $request->altura ? round($request->peso / (($request->altura / 100) ** 2), 2) : null,
            'porcentaje_grasa' => $seguimiento->porcentaje_grasa,
            'observaciones' => $request->observaciones,
            'fecha_registro' => $request->fecha_registro,
        ]);

        return redirect()->route('seguimientos.index', $seguimiento->rutina_usuario_id);
    }

    public function destroy(Seguimiento $seguimiento)
    {
        $id = $seguimiento->rutina_usuario_id;
        $seguimiento->delete();
        return redirect()->route('seguimientos.index', $id);
    }
}

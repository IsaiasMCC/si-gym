<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeguimientoController extends Controller
{
    public function index()
    {
        return Inertia::render('Seguimientos/Index', [
            'seguimientos' => Seguimiento::with('usuario')
                ->orderByDesc('fecha_registro')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Seguimientos/Create', [
            'usuarios' => User::orderBy('nombres')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
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
            'user_id' => $request->user_id,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'imc' => $imc,
            'porcentaje_grasa' => null, // se puede calcular o dejar nulo
            'observaciones' => $request->observaciones,
            'fecha_registro' => $request->fecha_registro,
        ]);

        return redirect()->route('seguimientos.index');
    }


    public function edit(Seguimiento $seguimiento)
    {
        return Inertia::render('Seguimientos/Edit', [
            'seguimiento' => $seguimiento->load('usuario'),
            'usuarios' => User::orderBy('nombres')->get(),
        ]);
    }

    public function update(Request $request, Seguimiento $seguimiento)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'fecha_registro' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $seguimiento->update([
            'user_id' => $request->user_id,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'imc' => $request->peso && $request->altura ? round($request->peso / (($request->altura / 100) ** 2), 2) : null,
            'porcentaje_grasa' => $seguimiento->porcentaje_grasa,
            'observaciones' => $request->observaciones,
            'fecha_registro' => $request->fecha_registro,
        ]);

        return redirect()->route('seguimientos.index');
    }

    public function destroy(Seguimiento $seguimiento)
    {
        $seguimiento->delete();
        return redirect()->route('seguimientos.index');
    }
}

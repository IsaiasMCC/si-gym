<?php

namespace App\Http\Controllers;

use App\Models\Avance;
use Illuminate\Http\Request;

class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notas' => 'required|string',
            'estado_avance' => 'required|string',
            'presupuesto_servicio_id' => 'required|exists:presupuesto_servicios,id',
            'foto' => 'nullable|image|max:4096'
        ]);

        $data = $request->only(['notas', 'estado_avance', 'presupuesto_servicio_id']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('avances', 'public');
        }

        $avance = Avance::create($data);

        return back()->with([
            'nuevoAvance' => $avance
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Avance $avance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Avance $avance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Avance $avance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avance $avance)
    {
        //
    }
}

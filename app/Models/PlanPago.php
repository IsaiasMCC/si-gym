<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = 'plan_pagos';
    protected $fillable = [
        'suscripcion_id',
        'fecha',
        'fecha_vencimiento',
        'monto',
        'saldo',
        'estado'
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Subscripcion::class, 'suscripcion_id');
    }
    public function subscripcion()
    {
        return $this->belongsTo(Subscripcion::class, 'suscripcion_id');
    }

    
}

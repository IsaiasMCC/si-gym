<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_pago_id',
        'monto',
        'metodo_pago',
        'fecha_pago',
        'estado',
        'referencia'
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Subscripcion::class);
    }
    public function subscripcion()
    {
        return $this->belongsTo(Subscripcion::class, 'subscripcion_id');
    }

    public function planPago()
    {
        return $this->belongsTo(PlanPago::class, 'plan_pago_id');
    }
    

}

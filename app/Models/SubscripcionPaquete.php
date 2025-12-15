<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscripcionPaquete extends Model
{
    protected $table = 'subscripcion_paquetes';
    protected $fillable = [
        'subscripcion_id',
        'paquete_id',
        'estado'
    ];

    public function subscripcion()
    {
        return $this->belongsTo(Subscripcion::class, 'subscripcion_id');
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class, 'paquete_id');
    }
}

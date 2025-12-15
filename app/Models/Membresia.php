<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'duracion_dias',
        'precio_base',
        'descripcion',
        'estado'
    ];

    public function paquetes()
    {
        return $this->hasMany(Paquete::class);
    }

    public function suscripciones()
    {
        return $this->hasMany(Subscripcion::class);
    }
}

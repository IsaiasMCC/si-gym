<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'membresia_id',
        'nombre',
        'descripcion',
        'precio_adicional',
        'estado'
    ];

    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }

    public function suscripciones()
    {
        return $this->hasMany(Subscripcion::class);
    }
}

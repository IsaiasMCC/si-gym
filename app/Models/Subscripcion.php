<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscripcion extends Model
{   
    protected $table = 'subscripciones';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membresia_id',
        'paquete_id',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }

    public function planPagos()
    {
        return $this->hasMany(PlanPago::class, 'suscripcion_id');
    }

    public function paquetes()
    {
        return $this->hasMany(SubscripcionPaquete::class, 'subscripcion_id');
    }
}

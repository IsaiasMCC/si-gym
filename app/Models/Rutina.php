<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rutina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'nivel',
        'objetivo',
        'entrenador_id'
    ];

    public function entrenador()
    {
        return $this->belongsTo(User::class, 'entrenador_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'rutina_usuario')
                    ->withPivot('fecha_asignacion', 'estado')
                    ->withTimestamps();
    }
}

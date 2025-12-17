<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'rutina_usuario_id',
        'peso',
        'altura',
        'imc',
        'porcentaje_grasa',
        'observaciones',
        'fecha_registro'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($seguimiento) {
            $seguimiento->calcularIMC();
        });

        static::updating(function ($seguimiento) {
            $seguimiento->calcularIMC();
        });
    }

    public function calcularIMC()
    {
        if ($this->peso && $this->altura) {
            $this->imc = round($this->peso / ($this->altura * $this->altura), 2);
        }
    }
}

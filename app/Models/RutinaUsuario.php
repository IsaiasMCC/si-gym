<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutinaUsuario extends Model
{
    protected $table = 'rutina_usuarios';
    protected $fillable = [
        'rutina_id',
        'user_id',
        'fecha_asignacion',
        'estado'
    ];

    public function rutina()
    {
        return $this->belongsTo(Rutina::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

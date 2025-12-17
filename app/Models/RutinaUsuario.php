<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutinaUsuario extends Model
{
    protected $table = 'rutina_usuarios';
    protected $fillable = [
        'rutina_id',
        'cliente_id',
        'instructor_id',
        'fecha_asignacion',
        'estado'
    ];

    public function rutina()
    {
        return $this->belongsTo(Rutina::class);
    }
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
    
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}

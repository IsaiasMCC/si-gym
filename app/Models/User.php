<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'email',
        'password',
        'estado',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    // Rol del usuario
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subscripciones()
    {
        return $this->hasMany(Subscripcion::class, 'user_id');
    }

    // Password solo si envían uno nuevo
    public function setPasswordAttribute($password)
    {
        if ($password !== null && $password !== '') {
            $this->attributes['password'] = Hash::make($password);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UsuarioSeeder::class);
        // Primero las tablas básicas
        $this->call(MembresiaSeeder::class);

        // Después los paquetes asociados a las membresías
        $this->call(PaqueteSeeder::class);

        // Luego las rutinas (con entrenadores ya creados en users)
        $this->call(RutinasSeeder::class);

        // Finalmente, asignar rutinas a los usuarios
        $this->call(RutinaUsuariosSeeder::class);
    }
}

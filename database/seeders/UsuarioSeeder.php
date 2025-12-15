<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Administrador'], ['guard_name' => 'web']);
        $roleSuperAdmin->syncPermissions(Permission::all());
        
        $admin = User::factory()->create([
            'ci' => '12345678',
            'nombres' => 'John',
            'apellidos' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'estado' => true,
            'role_id' => 1,
        ]);

        if (!$admin->hasRole('Administrador')) {
            $admin->assignRole($roleSuperAdmin);
        }

        User::factory()->create([
            'ci' => '464855785',
            'nombres' => 'Maria',
            'apellidos' => 'Mendez Gutierrez',
            'email' => 'maria@gmail.com',
            'password' => '12345678',
            'estado' => true,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'ci' => '27490285254',
            'nombres' => 'Julio Cesar',
            'apellidos' => 'Iglesias Torrico',
            'email' => 'julio@gmail.com',
            'password' => '12345678',
            'estado' => true,
            'role_id' => 2,
        ]);

        User::factory()->create([
            'ci' => '5896325',
            'nombres' => 'Camilo',
            'apellidos' => 'Maldonado Rojas',
            'email' => 'camilo@gmail.com',
            'password' => '12345678',
            'estado' => true,
            'role_id' => 3,
        ]);
    }
}

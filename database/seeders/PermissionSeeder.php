<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Módulo Roles
            'roles visualizar',
            'roles agregar',
            'roles editar',
            'roles eliminar',
            'roles permisos',       

            // Módulo Usuarios
            'usuarios visualizar',
            'usuarios agregar',
            'usuarios editar',
            'usuarios eliminar',

            // Módulo Membresias
            'membresias visualizar',
            'membresias agregar',
            'membresias editar',
            'membresias eliminar',

            // Módulo Paquetes
            'paquetes visualizar',
            'paquetes agregar',
            'paquetes editar',
            'paquetes eliminar',

            // Módulo Rutinas
            'rutinas visualizar',
            'rutinas agregar',
            'rutinas editar',
            'rutinas eliminar',


            // Modulo de clientes
            'rutinas cliente visualizar',
            
            // Modulos de rutinas asignadas a clientes por entrenadores
            'rutinas entrenador visualizar',
            'rutinas entrenador agregar',
            'rutinas entrenador editar',
            'rutinas entrenador eliminar',

            //Modulo de seguimientos de clientes por entrenadores
            'seguimiento rutina visualizar',
            'seguimiento rutina agregar',
            'seguimiento rutina editar',
            'seguimiento rutina eliminar',
            

            // Módulo Reportes
            'reporte seguimiento visualizar',
            'reporte pagos visualizar',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}

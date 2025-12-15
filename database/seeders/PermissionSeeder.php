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

            // Módulo Servicios
            'servicios visualizar',
            'servicios agregar',
            'servicios editar',
            'servicios eliminar',

            // Módulo Métodos de Pago
            'metodos-pagos visualizar',
            'metodos-pagos agregar',
            'metodos-pagos editar',
            'metodos-pagos eliminar',

            // Módulo Reservas
            'reservas visualizar',
            'reservas agregar',
            'reservas editar',
            'reservas eliminar',
            'reservas cliente visualizar',
            'reservas cliente editar',
            'reservas empleado visualizar',
            'reservas empleado editar',

            //Catalogo
            'catalogo servicios visualizar',
            'catalogo servicios agregar',

            // Módulo Reportes
            'reporte servicios visualizar',
            'reporte ventas visualizar',
            'reporte avances visualizar',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MembresiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('membresias')->insert([
            [
                'nombre' => 'Básica',
                'duracion_dias' => 30,
                'precio_base' => 100.00,
                'descripcion' => 'Acceso limitado a las instalaciones.',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Estándar',
                'duracion_dias' => 90,
                'precio_base' => 250.00,
                'descripcion' => 'Acceso completo a todas las instalaciones.',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Premium',
                'duracion_dias' => 365,
                'precio_base' => 900.00,
                'descripcion' => 'Acceso completo + clases ilimitadas + asesoría personalizada.',
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Inactiva',
                'duracion_dias' => 30,
                'precio_base' => 50.00,
                'descripcion' => 'Membresía temporal desactivada.',
                'estado' => 'inactivo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

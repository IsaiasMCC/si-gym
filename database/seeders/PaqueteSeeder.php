<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaqueteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Supongamos que tenemos membresias con IDs 1, 2 y 3
        DB::table('paquetes')->insert([
            [
                'membresia_id' => 1,
                'nombre' => 'Paquete Básico: Entrenamiento Personal',
                'descripcion' => '1 sesión personal por semana incluida en la membresía básica.',
                'precio_adicional' => 50.00,
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'membresia_id' => 2,
                'nombre' => 'Paquete Estándar: Clases Ilimitadas',
                'descripcion' => 'Acceso ilimitado a todas las clases grupales del gimnasio.',
                'precio_adicional' => 100.00,
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'membresia_id' => 3,
                'nombre' => 'Paquete Premium: Spa y Nutrición',
                'descripcion' => 'Incluye sesiones de spa, sauna y asesoría nutricional personalizada.',
                'precio_adicional' => 300.00,
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'membresia_id' => 2,
                'nombre' => 'Paquete Estándar: Entrenamiento Avanzado',
                'descripcion' => 'Sesiones de entrenamiento avanzado 2 veces por semana.',
                'precio_adicional' => 80.00,
                'estado' => 'activo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

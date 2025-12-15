<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('servicios')->insert([
            [
                'nombre' => 'Mantenimiento General',
                'detalles' => 'Servicio completo de mantenimiento preventivo.',
                'precio' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cintas Transportadora',
                'detalles' => 'Cinta transportadora de alta resistencia para uso industrial.',
                'precio' => 600.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Excabadoras',
                'detalles' => 'Servicio de alquiler de excabadoras para construcción.',
                'precio' => 800.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

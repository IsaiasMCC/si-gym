<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RutinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // IDs de entrenadores existentes
        $entrenadores = [2, 3]; // Ajusta según los IDs que tengas

        DB::table('rutinas')->insert([
            [
                'nombre' => 'Rutina Principiante: Full Body',
                'descripcion' => 'Rutina para principiantes enfocada en todo el cuerpo, 3 veces por semana.',
                'nivel' => 'principiante',
                'objetivo' => 'Tonificar y fortalecer',
                'entrenador_id' => $entrenadores[0],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Rutina Intermedio: Piernas y Core',
                'descripcion' => 'Rutina de 4 días, enfocada en piernas y core, para usuarios con experiencia.',
                'nivel' => 'intermedio',
                'objetivo' => 'Definir y aumentar fuerza',
                'entrenador_id' => $entrenadores[1],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Rutina Avanzada: Hipertrofia',
                'descripcion' => 'Rutina avanzada de 5 días para hipertrofia muscular y resistencia.',
                'nivel' => 'avanzado',
                'objetivo' => 'Ganar masa muscular',
                'entrenador_id' => $entrenadores[1],
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

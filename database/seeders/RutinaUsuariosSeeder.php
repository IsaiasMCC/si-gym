<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RutinaUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // IDs de rutinas existentes
        $rutinas = [1, 2, 3]; // Ajusta según tus rutinas
        // IDs de clientes
        $clientes = [4]; // Ajusta según tus usuarios tipo cliente
        // IDs de instructores
        $instructores = [2, 3]; // Ajusta según tus usuarios tipo instructor

        $data = [];

        foreach ($clientes as $cliente) {
            // Asignar 1 a 2 rutinas por cliente
            $numRutinas = rand(1, 2);
            $assignedRutinas = array_rand(array_flip($rutinas), $numRutinas);

            // Asegurarse de que sea array
            $assignedRutinas = is_array($assignedRutinas) ? $assignedRutinas : [$assignedRutinas];

            foreach ($assignedRutinas as $rutinaId) {
                $instructorId = $instructores[array_rand($instructores)];
                $data[] = [
                    'rutina_id' => $rutinaId,
                    'cliente_id' => $cliente,
                    'instructor_id' => $instructorId,
                    'fecha_asignacion' => Carbon::today()->subDays(rand(0, 30)),
                    'estado' => 'activa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('rutina_usuarios')->insert($data);
    }
}

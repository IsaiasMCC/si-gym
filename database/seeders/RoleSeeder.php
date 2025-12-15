<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Administrador', 'guard_name' => 'web'],
            ['name' => 'Entrenador', 'guard_name' => 'web'],
            ['name' => 'Cliente', 'guard_name' => 'web'],
        ]);
    }
}

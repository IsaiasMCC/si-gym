<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('metodo_pagos')->insert([
            [
                'nombre' => 'Efectivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tarjeta de Crédito',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tarjeta de Débito',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Transferencia Bancaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'QR (Banco / Billetera Digital)',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

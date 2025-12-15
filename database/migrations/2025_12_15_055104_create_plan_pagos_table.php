<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_pago', ['contado', 'credito']);
            $table->foreignId('suscripcion_id')->constrained('subscripciones')->cascadeOnDelete();
            $table->date('fecha');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 8, 2);
            $table->decimal('saldo', 8, 2)->default(0);
            $table->enum('estado', ['pendiente', 'pagado', 'vencido'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_pagos');
    }
};

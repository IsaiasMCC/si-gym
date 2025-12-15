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
        Schema::create('subscripcion_paquetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscripcion_id')->constrained('subscripciones')->cascadeOnDelete();
            $table->foreignId('paquete_id')->nullable()->constrained('paquetes')->nullOnDelete();
            $table->enum('estado', ['activa', 'vencida', 'cancelada'])->default('activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscripcion_paquetes');
    }
};

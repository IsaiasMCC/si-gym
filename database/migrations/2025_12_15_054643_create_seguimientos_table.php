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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_usuario_id')->constrained('rutina_usuarios')->cascadeOnDelete();
            $table->decimal('peso', 5, 2);
            $table->decimal('altura', 4, 2);
            $table->decimal('imc', 5, 2)->nullable();
            $table->decimal('porcentaje_grasa', 4, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->date('fecha_registro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};

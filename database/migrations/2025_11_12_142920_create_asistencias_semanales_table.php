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
        Schema::create('asistencias_semanales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained('personal')->onDelete('cascade');
            $table->date('fecha');
            $table->string('dia_semana', 20); // Lunes, Martes, etc.
            $table->time('entrada_hora');
            $table->time('salida_hora')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['presente', 'ausente', 'permiso', 'tardanza'])->default('presente');
            $table->foreignId('registrado_por')->nullable()->constrained('personal')->onDelete('set null');
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('fecha');
            $table->index(['personal_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias_semanales');
    }
};

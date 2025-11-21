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
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('cargo'); // ej. producción, despacho, administración
            $table->string('area');  // ej. planta, oficina, distribución
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->boolean('tiene_acceso')->default(false);
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('email');
            $table->index('estado');
            $table->index('cargo');
            $table->index('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * Tabla: tipos_producto
     * Descripción: Catálogo de tipos de producto para clasificación
     */
    public function up(): void
    {
        Schema::create('tipos_producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique()->comment('Nombre del tipo de producto (ej: Botellón, Bolsa, Saborizada)');
            $table->string('codigo', 20)->unique()->comment('Código del tipo de producto');
            $table->text('descripcion')->nullable()->comment('Descripción del tipo de producto');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->comment('Estado del tipo');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('estado');
            $table->index('codigo');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_producto');
    }
};

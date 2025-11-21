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
     * Tabla: vehiculos
     * Descripción: Catálogo de vehículos de la flota de distribución
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 10)->unique()->comment('Placa del vehículo');
            $table->string('modelo', 100)->nullable()->comment('Modelo del vehículo');
            $table->enum('estado', ['activo', 'mantenimiento', 'inactivo'])->default('activo')->comment('Estado operativo del vehículo');
            $table->integer('capacidad')->unsigned()->nullable()->comment('Capacidad de carga en unidades');
            $table->text('observacion')->nullable()->comment('Observaciones del vehículo');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('estado');
            $table->index('placa');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};

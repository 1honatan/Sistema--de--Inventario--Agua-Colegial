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
     * Tabla: empleados
     * Descripción: Almacena información de empleados de la planta
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Nombre completo del empleado');
            $table->string('cedula', 20)->unique()->nullable()->comment('Cédula de identidad');
            $table->string('telefono', 20)->nullable()->comment('Teléfono de contacto');
            $table->string('direccion', 200)->nullable()->comment('Dirección domiciliaria');
            $table->date('fecha_ingreso')->nullable()->comment('Fecha de ingreso a la empresa');
            $table->foreignId('id_usuario')->nullable()->constrained('usuarios')->onDelete('set null')->comment('Usuario asociado (opcional)');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('cedula');
            $table->index('id_usuario');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};

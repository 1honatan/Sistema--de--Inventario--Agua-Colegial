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
     * Tabla: productos
     * Descripción: Catálogo de productos (botellón, bolsa, agua saborizada, etc.)
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Nombre del producto (ej: Botellón 20L)');
            $table->string('tipo', 100)->comment('Tipo de producto (botellón, bolsa, saborizada, limón, gelatina, bolito, hielo)');
            $table->string('unidad_medida', 50)->comment('Unidad de medida (unidad, litro, kilogramo)');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->comment('Estado del producto');
            $table->timestamp('fecha_registro')->useCurrent()->comment('Fecha de registro del producto');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('tipo');
            $table->index('estado');
            $table->index(['tipo', 'estado']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

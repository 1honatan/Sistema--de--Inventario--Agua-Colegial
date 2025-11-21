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
     * Tabla: inventario
     * Descripción: Registro de movimientos de inventario (entradas y salidas)
     */
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('productos')->onDelete('restrict')->comment('Producto del movimiento');
            $table->enum('tipo_movimiento', ['entrada', 'salida'])->comment('Tipo de movimiento de inventario');
            $table->integer('cantidad')->unsigned()->comment('Cantidad del movimiento');
            $table->dateTime('fecha_movimiento')->comment('Fecha y hora del movimiento');
            $table->text('observacion')->nullable()->comment('Observaciones adicionales del movimiento');
            $table->timestamps();

            // Índices para optimizar consultas (CRITICAL: stock en <3 segundos)
            $table->index('id_producto');
            $table->index('tipo_movimiento');
            $table->index('fecha_movimiento');
            $table->index(['id_producto', 'tipo_movimiento']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};

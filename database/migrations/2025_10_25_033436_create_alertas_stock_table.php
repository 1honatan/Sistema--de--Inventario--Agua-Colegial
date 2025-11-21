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
     * Tabla: alertas_stock
     * Descripción: Sistema de alertas para niveles bajos de inventario
     */
    public function up(): void
    {
        Schema::create('alertas_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('productos')->onDelete('cascade')->comment('Producto asociado a la alerta');
            $table->integer('cantidad_minima')->unsigned()->comment('Cantidad mínima de stock para generar alerta');
            $table->integer('cantidad_actual')->unsigned()->nullable()->comment('Cantidad actual en stock al generar la alerta');
            $table->enum('estado_alerta', ['activa', 'atendida', 'ignorada'])->default('activa')->comment('Estado de la alerta');
            $table->enum('nivel_urgencia', ['baja', 'media', 'alta', 'critica'])->default('media')->comment('Nivel de urgencia de la alerta');
            $table->dateTime('fecha_alerta')->comment('Fecha y hora en que se generó la alerta');
            $table->dateTime('fecha_atencion')->nullable()->comment('Fecha y hora en que se atendió la alerta');
            $table->text('observaciones')->nullable()->comment('Observaciones sobre la alerta');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('id_producto');
            $table->index('estado_alerta');
            $table->index('nivel_urgencia');
            $table->index('fecha_alerta');
            $table->index(['id_producto', 'estado_alerta']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_stock');
    }
};

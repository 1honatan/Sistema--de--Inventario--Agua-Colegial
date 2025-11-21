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
     * Tabla: produccion
     * Descripción: Registro de lotes de producción diaria
     */
    public function up(): void
    {
        Schema::create('produccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('productos')->onDelete('restrict')->comment('Producto fabricado');
            $table->foreignId('id_personal')->constrained('personal')->onDelete('restrict')->comment('Personal responsable de la producción');
            $table->string('lote', 100)->unique()->comment('Código único de lote (PROD-YYYYMMDD-NNNN)');
            $table->integer('cantidad')->unsigned()->comment('Cantidad producida');
            $table->date('fecha_produccion')->comment('Fecha de producción del lote');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('lote');
            $table->index('fecha_produccion');
            $table->index('id_producto');
            $table->index(['fecha_produccion', 'id_producto']);
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccion');
    }
};

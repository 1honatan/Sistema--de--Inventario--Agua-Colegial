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
     * Agrega campo id_tipo_producto para clasificar productos por tipo
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Agregar relación con tipos_producto (nullable para compatibilidad con datos existentes)
            $table->foreignId('id_tipo_producto')
                ->nullable()
                ->after('tipo')
                ->constrained('tipos_producto')
                ->onDelete('set null')
                ->comment('Tipo de producto según catálogo');

            // Índice para optimizar consultas por tipo
            $table->index('id_tipo_producto');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_producto']);
            $table->dropIndex(['id_tipo_producto']);
            $table->dropColumn('id_tipo_producto');
        });
    }
};

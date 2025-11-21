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
     * Agrega campos de trazabilidad a la tabla inventario
     */
    public function up(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->string('origen', 200)->nullable()->after('cantidad')->comment('Origen del movimiento (ej: Producción, Proveedor, Ajuste)');
            $table->string('destino', 200)->nullable()->after('origen')->comment('Destino del movimiento (ej: Almacén, Cliente, Merma)');
            $table->string('referencia', 100)->nullable()->after('destino')->comment('Número de referencia o documento asociado');
            $table->foreignId('id_usuario')->nullable()->after('referencia')->constrained('usuarios')->onDelete('set null')->comment('Usuario que registró el movimiento');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropColumn(['origen', 'destino', 'referencia', 'id_usuario']);
        });
    }
};

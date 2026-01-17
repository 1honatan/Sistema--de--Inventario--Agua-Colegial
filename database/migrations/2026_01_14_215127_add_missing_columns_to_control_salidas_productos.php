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
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            // Agregar columnas faltantes si no existen
            if (!Schema::hasColumn('control_salidas_productos', 'tipo_salida')) {
                $table->string('tipo_salida', 50)->nullable()->after('id');
            }
            if (!Schema::hasColumn('control_salidas_productos', 'chofer')) {
                $table->string('chofer', 255)->nullable()->after('nombre_distribuidor');
            }
            if (!Schema::hasColumn('control_salidas_productos', 'responsable_venta')) {
                $table->string('responsable_venta', 255)->nullable()->after('chofer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            if (Schema::hasColumn('control_salidas_productos', 'tipo_salida')) {
                $table->dropColumn('tipo_salida');
            }
            if (Schema::hasColumn('control_salidas_productos', 'chofer')) {
                $table->dropColumn('chofer');
            }
            if (Schema::hasColumn('control_salidas_productos', 'responsable_venta')) {
                $table->dropColumn('responsable_venta');
            }
        });
    }
};

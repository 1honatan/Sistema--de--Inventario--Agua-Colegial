<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Agrega columnas faltantes a control_insumos para coincidir con el controlador:
     * - producto_insumo (renombrado de nombre_insumo)
     * - numero_lote
     * - fecha_vencimiento
     * - responsable
     */
    public function up(): void
    {
        Schema::table('control_insumos', function (Blueprint $table) {
            // Agregar columna producto_insumo si no existe
            if (!Schema::hasColumn('control_insumos', 'producto_insumo')) {
                $table->string('producto_insumo')->after('fecha')->nullable();
            }

            // Agregar numero_lote si no existe
            if (!Schema::hasColumn('control_insumos', 'numero_lote')) {
                $table->string('numero_lote', 100)->nullable()->after('unidad_medida');
            }

            // Agregar fecha_vencimiento si no existe
            if (!Schema::hasColumn('control_insumos', 'fecha_vencimiento')) {
                $table->date('fecha_vencimiento')->nullable()->after('numero_lote');
            }

            // Agregar responsable si no existe
            if (!Schema::hasColumn('control_insumos', 'responsable')) {
                $table->string('responsable')->nullable()->after('fecha_vencimiento');
            }
        });

        // Copiar datos de nombre_insumo a producto_insumo si nombre_insumo existe
        if (Schema::hasColumn('control_insumos', 'nombre_insumo')) {
            DB::statement('UPDATE control_insumos SET producto_insumo = nombre_insumo WHERE producto_insumo IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_insumos', function (Blueprint $table) {
            if (Schema::hasColumn('control_insumos', 'producto_insumo')) {
                $table->dropColumn('producto_insumo');
            }
            if (Schema::hasColumn('control_insumos', 'numero_lote')) {
                $table->dropColumn('numero_lote');
            }
            if (Schema::hasColumn('control_insumos', 'fecha_vencimiento')) {
                $table->dropColumn('fecha_vencimiento');
            }
            if (Schema::hasColumn('control_insumos', 'responsable')) {
                $table->dropColumn('responsable');
            }
        });
    }
};

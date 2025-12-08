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
        // 1. Agregar FK en control_produccion_productos (faltaba producto_id)
        try {
            Schema::table('control_produccion_productos', function (Blueprint $table) {
                $table->foreign('producto_id')
                    ->references('id')
                    ->on('productos')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // FK ya existe
        }

        // 2. Agregar FK en alertas_stock (id_producto)
        try {
            Schema::table('alertas_stock', function (Blueprint $table) {
                $table->foreign('id_producto')
                    ->references('id')
                    ->on('productos')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // FK ya existe
        }

        // 3. Agregar FK en productos (id_tipo_producto) - si no existe
        try {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreign('id_tipo_producto')
                    ->references('id')
                    ->on('tipos_producto')
                    ->onDelete('set null');
            });
        } catch (\Exception $e) {
            // FK ya existe
        }

        // Nota: Las siguientes tablas NO necesitan FK porque son tablas maestras/independientes:
        // - roles (tabla maestra de roles)
        // - personal (tabla maestra de empleados)
        // - tipos_producto (tabla maestra de tipos)
        // - vehiculos (tabla maestra de vehículos)
        // - control_produccion_diaria (tabla principal, no depende de otras)
        // - control_fosa_septica (registros independientes)
        // - control_fumigacion (registros independientes)
        // - control_insumos (registros independientes)
        // - control_tanques_agua (registros independientes)
        // - control_salidas_productos (ya tiene vehiculo_placa como FK opcional)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('control_produccion_productos', function (Blueprint $table) {
                $table->dropForeign(['producto_id']);
            });
        } catch (\Exception $e) {
            //
        }

        try {
            Schema::table('alertas_stock', function (Blueprint $table) {
                $table->dropForeign(['id_producto']);
            });
        } catch (\Exception $e) {
            //
        }

        try {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropForeign(['id_tipo_producto']);
            });
        } catch (\Exception $e) {
            //
        }
    }
};

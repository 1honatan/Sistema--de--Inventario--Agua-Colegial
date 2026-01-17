<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migración de optimización para escala de 300+ millones de registros
 * Agrega índices compuestos y optimizaciones para consultas frecuentes
 */
return new class extends Migration
{
    public function up(): void
    {
        // Índices para control_produccion_diaria (tabla de alto volumen)
        Schema::table('control_produccion_diaria', function (Blueprint $table) {
            if (!$this->indexExists('control_produccion_diaria', 'idx_produccion_fecha_turno')) {
                $table->index(['fecha', 'turno'], 'idx_produccion_fecha_turno');
            }
            if (!$this->indexExists('control_produccion_diaria', 'idx_produccion_responsable')) {
                $table->index('responsable', 'idx_produccion_responsable');
            }
        });

        // Índices para control_salidas_productos (tabla de alto volumen)
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            if (!$this->indexExists('control_salidas_productos', 'idx_salidas_fecha')) {
                $table->index('fecha', 'idx_salidas_fecha');
            }
            if (!$this->indexExists('control_salidas_productos', 'idx_salidas_fecha_vehiculo')) {
                $table->index(['fecha', 'vehiculo_placa'], 'idx_salidas_fecha_vehiculo');
            }
            if (!$this->indexExists('control_salidas_productos', 'idx_salidas_tipo')) {
                $table->index('tipo_salida', 'idx_salidas_tipo');
            }
            if (!$this->indexExists('control_salidas_productos', 'idx_salidas_distribuidor')) {
                $table->index('nombre_distribuidor', 'idx_salidas_distribuidor');
            }
        });

        // Índices para inventario (tabla de muy alto volumen)
        Schema::table('inventario', function (Blueprint $table) {
            if (!$this->indexExists('inventario', 'idx_inventario_producto_fecha')) {
                $table->index(['id_producto', 'fecha_movimiento'], 'idx_inventario_producto_fecha');
            }
            if (!$this->indexExists('inventario', 'idx_inventario_created')) {
                $table->index('created_at', 'idx_inventario_created');
            }
        });

        // Índices para control_tanques_agua
        Schema::table('control_tanques_agua', function (Blueprint $table) {
            if (!$this->indexExists('control_tanques_agua', 'idx_tanques_fecha_limpieza')) {
                $table->index('fecha_limpieza', 'idx_tanques_fecha_limpieza');
            }
            if (!$this->indexExists('control_tanques_agua', 'idx_tanques_proxima')) {
                $table->index('proxima_limpieza', 'idx_tanques_proxima');
            }
        });

        // Índices para control_mantenimiento_equipos
        Schema::table('control_mantenimiento_equipos', function (Blueprint $table) {
            if (!$this->indexExists('control_mantenimiento_equipos', 'idx_mantenimiento_fecha')) {
                $table->index('fecha', 'idx_mantenimiento_fecha');
            }
            if (!$this->indexExists('control_mantenimiento_equipos', 'idx_mantenimiento_equipo')) {
                $table->index('equipo', 'idx_mantenimiento_equipo');
            }
        });

        // Índices para asistencias_semanales
        Schema::table('asistencias_semanales', function (Blueprint $table) {
            if (!$this->indexExists('asistencias_semanales', 'idx_asistencia_fecha')) {
                $table->index('fecha', 'idx_asistencia_fecha');
            }
            if (!$this->indexExists('asistencias_semanales', 'idx_asistencia_personal_fecha')) {
                $table->index(['personal_id', 'fecha'], 'idx_asistencia_personal_fecha');
            }
            if (!$this->indexExists('asistencias_semanales', 'idx_asistencia_estado')) {
                $table->index('estado', 'idx_asistencia_estado');
            }
        });

        // Índices para personal
        Schema::table('personal', function (Blueprint $table) {
            if (!$this->indexExists('personal', 'idx_personal_estado')) {
                $table->index('estado', 'idx_personal_estado');
            }
            if (!$this->indexExists('personal', 'idx_personal_cargo')) {
                $table->index('cargo', 'idx_personal_cargo');
            }
        });

        // Índices para historial_reportes
        Schema::table('historial_reportes', function (Blueprint $table) {
            if (!$this->indexExists('historial_reportes', 'idx_reportes_tipo_fecha')) {
                $table->index(['tipo', 'created_at'], 'idx_reportes_tipo_fecha');
            }
        });

        // Optimizar configuración de tablas InnoDB
        $tables = [
            'control_produccion_diaria',
            'control_salidas_productos',
            'inventario',
            'control_tanques_agua',
            'control_mantenimiento_equipos',
            'asistencias_semanales',
            'historial_reportes'
        ];

        foreach ($tables as $tableName) {
            DB::statement("OPTIMIZE TABLE `{$tableName}`");
        }
    }

    public function down(): void
    {
        $indexes = [
            'control_produccion_diaria' => ['idx_produccion_fecha_turno', 'idx_produccion_responsable'],
            'control_salidas_productos' => ['idx_salidas_fecha', 'idx_salidas_fecha_vehiculo', 'idx_salidas_tipo', 'idx_salidas_distribuidor'],
            'inventario' => ['idx_inventario_producto_fecha', 'idx_inventario_created'],
            'control_tanques_agua' => ['idx_tanques_fecha_limpieza', 'idx_tanques_proxima'],
            'control_mantenimiento_equipos' => ['idx_mantenimiento_fecha', 'idx_mantenimiento_equipo'],
            'asistencias_semanales' => ['idx_asistencia_fecha', 'idx_asistencia_personal_fecha', 'idx_asistencia_estado'],
            'personal' => ['idx_personal_estado', 'idx_personal_cargo'],
            'historial_reportes' => ['idx_reportes_tipo_fecha']
        ];

        foreach ($indexes as $table => $indexNames) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($indexNames) {
                foreach ($indexNames as $indexName) {
                    try {
                        $tableBlueprint->dropIndex($indexName);
                    } catch (\Exception $e) {
                        // Ignorar si no existe
                    }
                }
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};

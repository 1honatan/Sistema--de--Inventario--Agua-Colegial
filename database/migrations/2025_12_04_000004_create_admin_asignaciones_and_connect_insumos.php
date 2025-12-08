<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1. Conectar control_insumos con personal
     * 2. Crear tabla admin_asignaciones para control centralizado
     */
    public function up(): void
    {
        // ============================================
        // 1. CONECTAR CONTROL_INSUMOS CON PERSONAL
        // ============================================
        if (Schema::hasColumn('control_insumos', 'responsable')) {
            Schema::table('control_insumos', function (Blueprint $table) {
                $table->foreignId('id_responsable')->nullable()->after('fecha_vencimiento');
            });

            Schema::table('control_insumos', function (Blueprint $table) {
                $table->renameColumn('responsable', 'responsable_texto');
            });

            Schema::table('control_insumos', function (Blueprint $table) {
                $table->foreign('id_responsable')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // ============================================
        // 2. CREAR TABLA ADMIN_ASIGNACIONES
        // ============================================
        Schema::create('admin_asignaciones', function (Blueprint $table) {
            $table->id();

            // Empleado asignado
            $table->foreignId('id_personal')
                ->constrained('personal')
                ->onDelete('cascade')
                ->comment('Empleado asignado');

            // Tipo de asignación
            $table->enum('tipo_asignacion', [
                'chofer',                    // Chofer de vehículo
                'responsable_vehiculo',      // Responsable de vehículo
                'mantenimiento',             // Técnico de mantenimiento
                'produccion',                // Personal de producción
                'fumigacion',                // Responsable de fumigación
                'tanques',                   // Limpieza de tanques
                'fosa_septica',              // Limpieza de fosa
                'insumos',                   // Control de insumos
                'supervisor',                // Supervisor general
                'otro'                       // Otra asignación
            ])->comment('Tipo de tarea asignada');

            // Referencia al módulo (opcional, para vincular con registro específico)
            $table->string('modulo')->nullable()->comment('Módulo del sistema');
            $table->unsignedBigInteger('id_referencia')->nullable()->comment('ID del registro relacionado');

            // Detalles de la asignación
            $table->date('fecha_inicio')->comment('Fecha de inicio de asignación');
            $table->date('fecha_fin')->nullable()->comment('Fecha fin (null = indefinida)');
            $table->enum('estado', ['activa', 'suspendida', 'finalizada'])->default('activa');

            // Observaciones
            $table->text('descripcion')->nullable()->comment('Descripción de la asignación');
            $table->text('observaciones')->nullable();

            // Quién hizo la asignación
            $table->foreignId('asignado_por')
                ->constrained('usuarios')
                ->onDelete('cascade')
                ->comment('Usuario admin que hizo la asignación');

            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['id_personal', 'estado']);
            $table->index(['tipo_asignacion', 'estado']);
            $table->index(['modulo', 'id_referencia']);
        });

        // ============================================
        // 3. CREAR TABLA DE HISTORIAL DE ASIGNACIONES
        // ============================================
        Schema::create('admin_historial_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_asignacion')
                ->constrained('admin_asignaciones')
                ->onDelete('cascade');
            $table->string('accion')->comment('creada, modificada, suspendida, finalizada');
            $table->text('detalles')->nullable();
            $table->foreignId('realizado_por')
                ->constrained('usuarios')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar tablas de asignaciones
        Schema::dropIfExists('admin_historial_asignaciones');
        Schema::dropIfExists('admin_asignaciones');

        // Revertir control_insumos
        if (Schema::hasColumn('control_insumos', 'id_responsable')) {
            Schema::table('control_insumos', function (Blueprint $table) {
                $table->dropForeign(['id_responsable']);
                $table->dropColumn('id_responsable');
                $table->renameColumn('responsable_texto', 'responsable');
            });
        }
    }
};

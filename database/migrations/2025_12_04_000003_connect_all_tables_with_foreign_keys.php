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
     * Conectar todas las tablas con foreign keys lógicas:
     * - control_fosa_septica.id_responsable -> personal.id
     * - control_tanques_agua.id_responsable -> personal.id
     * - control_fumigacion.id_responsable -> personal.id
     * - vehiculos.id_responsable -> personal.id
     * - control_salidas_productos: ya tiene chofer como texto, mantener así
     */
    public function up(): void
    {
        // ============================================
        // 1. CONTROL_FOSA_SEPTICA
        // ============================================
        // Cambiar 'responsable' VARCHAR a 'id_responsable' BIGINT FK
        if (Schema::hasColumn('control_fosa_septica', 'responsable')) {
            // Agregar nueva columna id_responsable
            Schema::table('control_fosa_septica', function (Blueprint $table) {
                $table->foreignId('id_responsable')->nullable()->after('fecha_limpieza');
            });

            // Renombrar la columna antigua para mantener el dato original
            Schema::table('control_fosa_septica', function (Blueprint $table) {
                $table->renameColumn('responsable', 'responsable_texto');
            });

            // Agregar FK
            Schema::table('control_fosa_septica', function (Blueprint $table) {
                $table->foreign('id_responsable')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // ============================================
        // 2. CONTROL_TANQUES_AGUA
        // ============================================
        if (Schema::hasColumn('control_tanques_agua', 'responsable')) {
            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->foreignId('id_responsable')->nullable()->after('productos_desinfeccion');
            });

            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->renameColumn('responsable', 'responsable_texto');
            });

            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->foreign('id_responsable')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // Agregar FK para supervisado_por también
        if (Schema::hasColumn('control_tanques_agua', 'supervisado_por')) {
            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->foreignId('id_supervisor')->nullable()->after('id_responsable');
            });

            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->renameColumn('supervisado_por', 'supervisor_texto');
            });

            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->foreign('id_supervisor')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // ============================================
        // 3. CONTROL_FUMIGACION
        // ============================================
        if (Schema::hasColumn('control_fumigacion', 'responsable')) {
            Schema::table('control_fumigacion', function (Blueprint $table) {
                $table->foreignId('id_responsable')->nullable()->after('cantidad_producto');
            });

            Schema::table('control_fumigacion', function (Blueprint $table) {
                $table->renameColumn('responsable', 'responsable_texto');
            });

            Schema::table('control_fumigacion', function (Blueprint $table) {
                $table->foreign('id_responsable')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // ============================================
        // 4. VEHICULOS
        // ============================================
        if (Schema::hasColumn('vehiculos', 'responsable')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->foreignId('id_responsable')->nullable()->after('placa');
            });

            Schema::table('vehiculos', function (Blueprint $table) {
                $table->renameColumn('responsable', 'responsable_texto');
            });

            Schema::table('vehiculos', function (Blueprint $table) {
                $table->foreign('id_responsable')
                    ->references('id')
                    ->on('personal')
                    ->onDelete('set null');
            });
        }

        // ============================================
        // 5. CONTROL_SALIDAS_PRODUCTOS
        // ============================================
        // Agregar FK para chofer (mantener chofer como texto también)
        if (Schema::hasColumn('control_salidas_productos', 'chofer')) {
            // Verificar si no existe ya id_chofer
            if (!Schema::hasColumn('control_salidas_productos', 'id_chofer')) {
                Schema::table('control_salidas_productos', function (Blueprint $table) {
                    $table->foreignId('id_chofer')->nullable()->after('vehiculo_placa');
                });

                Schema::table('control_salidas_productos', function (Blueprint $table) {
                    $table->foreign('id_chofer')
                        ->references('id')
                        ->on('personal')
                        ->onDelete('set null');
                });
            }
        }

        // Agregar FK para responsable si existe
        if (Schema::hasColumn('control_salidas_productos', 'responsable')) {
            if (!Schema::hasColumn('control_salidas_productos', 'id_responsable_salida')) {
                Schema::table('control_salidas_productos', function (Blueprint $table) {
                    $table->foreignId('id_responsable_salida')->nullable();
                });

                Schema::table('control_salidas_productos', function (Blueprint $table) {
                    $table->foreign('id_responsable_salida')
                        ->references('id')
                        ->on('personal')
                        ->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir en orden inverso

        // control_salidas_productos
        if (Schema::hasColumn('control_salidas_productos', 'id_responsable_salida')) {
            Schema::table('control_salidas_productos', function (Blueprint $table) {
                $table->dropForeign(['id_responsable_salida']);
                $table->dropColumn('id_responsable_salida');
            });
        }

        if (Schema::hasColumn('control_salidas_productos', 'id_chofer')) {
            Schema::table('control_salidas_productos', function (Blueprint $table) {
                $table->dropForeign(['id_chofer']);
                $table->dropColumn('id_chofer');
            });
        }

        // vehiculos
        if (Schema::hasColumn('vehiculos', 'id_responsable')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->dropForeign(['id_responsable']);
                $table->dropColumn('id_responsable');
                $table->renameColumn('responsable_texto', 'responsable');
            });
        }

        // control_fumigacion
        if (Schema::hasColumn('control_fumigacion', 'id_responsable')) {
            Schema::table('control_fumigacion', function (Blueprint $table) {
                $table->dropForeign(['id_responsable']);
                $table->dropColumn('id_responsable');
                $table->renameColumn('responsable_texto', 'responsable');
            });
        }

        // control_tanques_agua
        if (Schema::hasColumn('control_tanques_agua', 'id_supervisor')) {
            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->dropForeign(['id_supervisor']);
                $table->dropColumn('id_supervisor');
                $table->renameColumn('supervisor_texto', 'supervisado_por');
            });
        }

        if (Schema::hasColumn('control_tanques_agua', 'id_responsable')) {
            Schema::table('control_tanques_agua', function (Blueprint $table) {
                $table->dropForeign(['id_responsable']);
                $table->dropColumn('id_responsable');
                $table->renameColumn('responsable_texto', 'responsable');
            });
        }

        // control_fosa_septica
        if (Schema::hasColumn('control_fosa_septica', 'id_responsable')) {
            Schema::table('control_fosa_septica', function (Blueprint $table) {
                $table->dropForeign(['id_responsable']);
                $table->dropColumn('id_responsable');
                $table->renameColumn('responsable_texto', 'responsable');
            });
        }
    }
};

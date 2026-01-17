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
        Schema::table('control_fosa_septica', function (Blueprint $table) {
            // Agregar tipo_fosa si no existe
            if (!Schema::hasColumn('control_fosa_septica', 'tipo_fosa')) {
                $table->string('tipo_fosa')->nullable()->after('fecha_limpieza');
            }

            // Agregar responsable si no existe
            if (!Schema::hasColumn('control_fosa_septica', 'responsable')) {
                $table->string('responsable')->nullable()->after('tipo_fosa');
            }

            // Hacer responsable_texto nullable
            if (Schema::hasColumn('control_fosa_septica', 'responsable_texto')) {
                $table->string('responsable_texto')->nullable()->change();
            }

            // Hacer id_responsable nullable
            if (Schema::hasColumn('control_fosa_septica', 'id_responsable')) {
                $table->unsignedBigInteger('id_responsable')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_fosa_septica', function (Blueprint $table) {
            if (Schema::hasColumn('control_fosa_septica', 'tipo_fosa')) {
                $table->dropColumn('tipo_fosa');
            }
            if (Schema::hasColumn('control_fosa_septica', 'responsable')) {
                $table->dropColumn('responsable');
            }
        });
    }
};

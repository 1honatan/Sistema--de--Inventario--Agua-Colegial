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
        // Agregar columna 'responsable' si no existe
        if (!Schema::hasColumn('vehiculos', 'responsable')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->string('responsable')->nullable()->after('placa');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vehiculos', 'responsable')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->dropColumn('responsable');
            });
        }
    }
};

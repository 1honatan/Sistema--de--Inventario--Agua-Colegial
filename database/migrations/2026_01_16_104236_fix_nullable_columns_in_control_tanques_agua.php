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
        Schema::table('control_tanques_agua', function (Blueprint $table) {
            // Hacer columnas nullable para evitar errores
            $table->string('responsable_texto')->nullable()->change();
            $table->string('supervisor_texto')->nullable()->change();
            $table->unsignedBigInteger('id_responsable')->nullable()->change();
            $table->unsignedBigInteger('id_supervisor')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_tanques_agua', function (Blueprint $table) {
            $table->string('responsable_texto')->nullable(false)->change();
            $table->string('supervisor_texto')->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Hace nombre_insumo nullable porque ahora usamos producto_insumo
     */
    public function up(): void
    {
        Schema::table('control_insumos', function (Blueprint $table) {
            $table->string('nombre_insumo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_insumos', function (Blueprint $table) {
            $table->string('nombre_insumo')->nullable(false)->change();
        });
    }
};

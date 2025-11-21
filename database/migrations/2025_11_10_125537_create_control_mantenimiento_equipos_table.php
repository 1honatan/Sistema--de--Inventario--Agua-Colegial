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
        Schema::create('control_mantenimiento_equipos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('equipo');
            $table->text('detalle_mantenimiento');
            $table->date('proxima_fecha')->nullable();
            $table->string('realizado_por');
            $table->string('supervisado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_mantenimiento_equipos');
    }
};

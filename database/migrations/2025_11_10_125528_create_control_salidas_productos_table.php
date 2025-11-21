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
        Schema::create('control_salidas_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_distribuidor');
            $table->date('fecha');

            // Días de la semana
            $table->integer('lunes')->default(0);
            $table->integer('martes')->default(0);
            $table->integer('miercoles')->default(0);
            $table->integer('jueves')->default(0);
            $table->integer('viernes')->default(0);
            $table->integer('sabado')->default(0);
            $table->integer('domingo')->default(0);

            // Productos
            $table->integer('retornos')->default(0);
            $table->integer('botellones')->default(0);
            $table->integer('bolo_grande')->default(0);
            $table->integer('bolo_pequeño')->default(0);
            $table->integer('gelatina')->default(0);
            $table->integer('agua_saborizada')->default(0);
            $table->integer('agua_limon')->default(0);
            $table->integer('agua_natural')->default(0);
            $table->integer('hielo')->default(0);
            $table->integer('dispenser')->default(0);
            $table->integer('choreados')->default(0);

            $table->time('hora_llegada')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_salidas_productos');
    }
};

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
        Schema::create('control_tanques_agua', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_limpieza');
            $table->string('nombre_tanque');
            $table->decimal('capacidad_litros', 10, 2)->nullable();
            $table->text('procedimiento_limpieza')->nullable();
            $table->text('productos_desinfeccion')->nullable();
            $table->string('responsable');
            $table->string('supervisado_por')->nullable();
            $table->date('proxima_limpieza')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_tanques_agua');
    }
};

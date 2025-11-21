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
        Schema::create('control_fosa_septica', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_limpieza');
            $table->string('responsable');
            $table->text('detalle_trabajo')->nullable();
            $table->string('empresa_contratada')->nullable();
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
        Schema::dropIfExists('control_fosa_septica');
    }
};

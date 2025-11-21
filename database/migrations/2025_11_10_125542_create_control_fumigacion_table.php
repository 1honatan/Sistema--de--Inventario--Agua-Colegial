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
        Schema::create('control_fumigacion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_fumigacion');
            $table->string('area_fumigada');
            $table->string('producto_utilizado');
            $table->decimal('cantidad_producto', 10, 2);
            $table->string('responsable');
            $table->string('empresa_contratada')->nullable();
            $table->date('proxima_fumigacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_fumigacion');
    }
};

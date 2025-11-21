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
        Schema::create('control_insumos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('nombre_insumo');
            $table->decimal('cantidad', 10, 2);
            $table->string('unidad_medida');
            $table->decimal('stock_actual', 10, 2);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->string('proveedor')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_insumos');
    }
};

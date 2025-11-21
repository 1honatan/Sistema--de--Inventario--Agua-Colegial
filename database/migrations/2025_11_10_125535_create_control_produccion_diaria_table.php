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
        // Tabla principal de producción diaria
        Schema::create('control_produccion_diaria', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Tabla de productos producidos
        Schema::create('control_produccion_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produccion_id')->constrained('control_produccion_diaria')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad')->default(0);
            $table->timestamps();
        });

        // Tabla de preparaciones realizadas
        Schema::create('control_produccion_preparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produccion_id')->constrained('control_produccion_diaria')->onDelete('cascade');
            $table->string('nombre_preparacion');
            $table->integer('cantidad')->default(0);
            $table->string('unidad_medida')->default('unidades');
            $table->timestamps();
        });

        // Tabla de salida de material
        Schema::create('control_produccion_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produccion_id')->constrained('control_produccion_diaria')->onDelete('cascade');
            $table->string('nombre_material');
            $table->decimal('cantidad', 10, 2)->default(0);
            $table->string('unidad_medida')->default('kg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_produccion_materiales');
        Schema::dropIfExists('control_produccion_preparaciones');
        Schema::dropIfExists('control_produccion_productos');
        Schema::dropIfExists('control_produccion_diaria');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar FK de vehiculo_placa en control_salidas_productos
        try {
            Schema::table('control_salidas_productos', function (Blueprint $table) {
                $table->foreign('vehiculo_placa')
                    ->references('placa')
                    ->on('vehiculos')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        } catch (\Exception $e) {
            // FK ya existe, continuar
        }

        // Agregar índice a inventario.origen si existe la columna
        if (Schema::hasColumn('inventario', 'origen')) {
            try {
                Schema::table('inventario', function (Blueprint $table) {
                    $table->index('origen');
                });
            } catch (\Exception $e) {
                // Índice ya existe, continuar
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('control_salidas_productos', function (Blueprint $table) {
                $table->dropForeign(['vehiculo_placa']);
            });
        } catch (\Exception $e) {
            // No existe la FK
        }

        if (Schema::hasColumn('inventario', 'origen')) {
            try {
                Schema::table('inventario', function (Blueprint $table) {
                    $table->dropIndex(['origen']);
                });
            } catch (\Exception $e) {
                // No existe el índice
            }
        }
    }
};

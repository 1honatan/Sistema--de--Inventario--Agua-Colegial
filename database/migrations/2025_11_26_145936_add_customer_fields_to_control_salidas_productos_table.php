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
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            $table->string('nombre_cliente')->nullable()->after('tipo_salida');
            $table->string('direccion_entrega')->nullable()->after('nombre_cliente');
            $table->string('telefono_cliente', 20)->nullable()->after('direccion_entrega');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            $table->dropColumn(['nombre_cliente', 'direccion_entrega', 'telefono_cliente']);
        });
    }
};

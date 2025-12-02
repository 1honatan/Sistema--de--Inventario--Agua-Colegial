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
        Schema::table('control_mantenimiento_equipos', function (Blueprint $table) {
            $table->foreignId('id_personal')->nullable()->after('fecha')->constrained('personal')->onDelete('set null');
            $table->json('productos_limpieza')->nullable()->after('detalle_mantenimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_mantenimiento_equipos', function (Blueprint $table) {
            $table->dropForeign(['id_personal']);
            $table->dropColumn(['id_personal', 'productos_limpieza']);
        });
    }
};

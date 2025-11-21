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
        Schema::table('control_produccion_diaria', function (Blueprint $table) {
            $table->string('responsable')->nullable()->after('fecha');
            $table->string('turno')->nullable()->after('responsable');
            $table->string('preparacion')->nullable()->after('turno');
            $table->integer('rollos_material')->default(0)->after('preparacion');
            $table->decimal('gasto_material', 10, 2)->default(0)->after('rollos_material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_produccion_diaria', function (Blueprint $table) {
            $table->dropColumn(['responsable', 'turno', 'preparacion', 'rollos_material', 'gasto_material']);
        });
    }
};

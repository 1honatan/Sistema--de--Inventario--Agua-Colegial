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
        Schema::table('control_tanques_agua', function (Blueprint $table) {
            if (!Schema::hasColumn('control_tanques_agua', 'responsable')) {
                $table->string('responsable')->nullable()->after('productos_desinfeccion');
            }
            if (!Schema::hasColumn('control_tanques_agua', 'supervisado_por')) {
                $table->string('supervisado_por')->nullable()->after('responsable');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_tanques_agua', function (Blueprint $table) {
            if (Schema::hasColumn('control_tanques_agua', 'responsable')) {
                $table->dropColumn('responsable');
            }
            if (Schema::hasColumn('control_tanques_agua', 'supervisado_por')) {
                $table->dropColumn('supervisado_por');
            }
        });
    }
};

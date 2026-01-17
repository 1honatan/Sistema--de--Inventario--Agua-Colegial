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
        Schema::table('control_fumigacion', function (Blueprint $table) {
            if (!Schema::hasColumn('control_fumigacion', 'responsable')) {
                $table->string('responsable')->nullable()->after('cantidad_producto');
            }

            // Hacer columnas existentes nullable
            if (Schema::hasColumn('control_fumigacion', 'id_responsable')) {
                $table->unsignedBigInteger('id_responsable')->nullable()->change();
            }
            if (Schema::hasColumn('control_fumigacion', 'responsable_texto')) {
                $table->string('responsable_texto')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_fumigacion', function (Blueprint $table) {
            if (Schema::hasColumn('control_fumigacion', 'responsable')) {
                $table->dropColumn('responsable');
            }
        });
    }
};

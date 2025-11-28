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
        Schema::table('personal', function (Blueprint $table) {
            if (!Schema::hasColumn('personal', 'direccion')) {
                $table->string('direccion')->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('personal', 'fecha_ingreso')) {
                $table->date('fecha_ingreso')->nullable()->after('area');
            }
            if (!Schema::hasColumn('personal', 'salario')) {
                $table->decimal('salario', 10, 2)->nullable()->after('fecha_ingreso');
            }
            if (!Schema::hasColumn('personal', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('salario');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $columns = ['direccion', 'fecha_ingreso', 'salario', 'observaciones'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('personal', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

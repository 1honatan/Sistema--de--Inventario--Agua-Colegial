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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId('id_personal')
                ->nullable()
                ->after('id_rol')
                ->constrained('personal')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index('id_personal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_personal']);
            $table->dropColumn('id_personal');
        });
    }
};

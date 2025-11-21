<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            $table->integer('retorno_botellones')->default(0)->after('retornos');
            $table->integer('retorno_bolo_grande')->default(0)->after('retorno_botellones');
            $table->integer('retorno_bolo_pequeno')->default(0)->after('retorno_bolo_grande');
            $table->integer('retorno_gelatina')->default(0)->after('retorno_bolo_pequeno');
            $table->integer('retorno_agua_saborizada')->default(0)->after('retorno_gelatina');
            $table->integer('retorno_agua_limon')->default(0)->after('retorno_agua_saborizada');
            $table->integer('retorno_agua_natural')->default(0)->after('retorno_agua_limon');
            $table->integer('retorno_hielo')->default(0)->after('retorno_agua_natural');
            $table->integer('retorno_dispenser')->default(0)->after('retorno_hielo');
        });
    }

    public function down(): void
    {
        Schema::table('control_salidas_productos', function (Blueprint $table) {
            $table->dropColumn([
                'retorno_botellones',
                'retorno_bolo_grande',
                'retorno_bolo_pequeno',
                'retorno_gelatina',
                'retorno_agua_saborizada',
                'retorno_agua_limon',
                'retorno_agua_natural',
                'retorno_hielo',
                'retorno_dispenser',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Crea una vista optimizada que calcula el stock actual de cada producto
     * en tiempo real basándose en los movimientos de entrada y salida.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_stock_actual AS
            SELECT
                p.id,
                p.nombre,
                p.tipo,
                p.unidad_medida,
                p.estado,
                tp.nombre AS tipo_producto,
                COALESCE(
                    (SELECT SUM(i.cantidad)
                     FROM inventario i
                     WHERE i.id_producto = p.id
                     AND i.tipo_movimiento = 'entrada'), 0
                ) - COALESCE(
                    (SELECT SUM(i.cantidad)
                     FROM inventario i
                     WHERE i.id_producto = p.id
                     AND i.tipo_movimiento = 'salida'), 0
                ) AS stock_actual
            FROM productos p
            LEFT JOIN tipos_producto tp ON p.id_tipo_producto = tp.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_stock_actual');
    }
};

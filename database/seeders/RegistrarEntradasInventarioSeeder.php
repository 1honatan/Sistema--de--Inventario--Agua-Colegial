<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrarEntradasInventarioSeeder extends Seeder
{
    /**
     * Registrar movimientos de entrada en inventario desde control_produccion_productos.
     */
    public function run(): void
    {
        // Obtener todas las producciones con sus productos
        $producciones = DB::table('control_produccion_diaria')
            ->whereBetween('fecha', ['2025-12-01', '2025-12-23'])
            ->orWhereBetween('fecha', ['2025-12-26', '2025-12-30'])
            ->get();

        $movimientos = [];

        foreach ($producciones as $produccion) {
            // Obtener los productos de esta producción
            $productos = DB::table('control_produccion_productos')
                ->where('produccion_id', $produccion->id)
                ->get();

            foreach ($productos as $producto) {
                $movimientos[] = [
                    'id_producto' => $producto->producto_id,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $producto->cantidad,
                    'fecha_movimiento' => $produccion->fecha . ' 08:00:00',
                    'origen' => 'Producción Diaria',
                    'observacion' => 'Entrada por producción del ' . date('d/m/Y', strtotime($produccion->fecha)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todos los movimientos
        if (!empty($movimientos)) {
            DB::table('inventario')->insert($movimientos);
            $this->command->info('✅ Se registraron ' . count($movimientos) . ' movimientos de entrada en inventario.');
        } else {
            $this->command->warn('⚠️ No hay producciones para registrar.');
        }
    }
}

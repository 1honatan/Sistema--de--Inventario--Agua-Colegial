<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Control\ProduccionDiaria;
use App\Models\Inventario;

class SincronizarProduccionInventarioSeeder extends Seeder
{
    /**
     * Sincronizar todas las producciones existentes con el inventario
     */
    public function run(): void
    {
        echo "🔄 Sincronizando producciones con inventario...\n\n";

        // Obtener todas las producciones
        $producciones = ProduccionDiaria::with('productos')->get();

        $totalProducciones = $producciones->count();
        $totalProductos = 0;
        $produccionesSincronizadas = 0;

        foreach ($producciones as $produccion) {
            // Primero eliminar cualquier entrada antigua
            $entradasAntiguasCount = Inventario::where('referencia', 'Producción #' . $produccion->id)->count();
            if ($entradasAntiguasCount > 0) {
                Inventario::where('referencia', 'Producción #' . $produccion->id)->delete();
                echo "🗑️  Eliminadas {$entradasAntiguasCount} entradas antiguas de Producción #{$produccion->id}\n";
            }

            // Sincronizar cada producto de la producción
            foreach ($produccion->productos as $productoProduccion) {
                Inventario::create([
                    'id_producto' => $productoProduccion->producto_id,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $productoProduccion->cantidad,
                    'origen' => 'Producción Diaria',
                    'destino' => 'Inventario',
                    'referencia' => 'Producción #' . $produccion->id,
                    'id_usuario' => null,
                    'fecha_movimiento' => $produccion->fecha,
                    'observacion' => 'Sincronización automática - Responsable: ' . $produccion->responsable,
                    'created_at' => $produccion->created_at,
                    'updated_at' => now(),
                ]);

                $totalProductos++;
            }

            $produccionesSincronizadas++;
            echo "✅ Producción #{$produccion->id} - {$produccion->productos->count()} productos sincronizados\n";
        }

        echo "\n📊 RESUMEN:\n";
        echo "   📦 Total producciones: {$totalProducciones}\n";
        echo "   ✅ Producciones sincronizadas: {$produccionesSincronizadas}\n";
        echo "   📥 Productos agregados al inventario: {$totalProductos}\n";
        echo "\n✅ Sincronización completada!\n";
    }
}

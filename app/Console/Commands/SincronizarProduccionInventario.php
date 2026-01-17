<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Control\ProduccionDiaria;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class SincronizarProduccionInventario extends Command
{
    protected $signature = 'produccion:sincronizar-inventario';
    protected $description = 'Sincroniza producciones con inventario';

    public function handle()
    {
        $this->info('Sincronizando producciones con inventario...');

        $producciones = ProduccionDiaria::with('productos.producto')->get();

        if ($producciones->isEmpty()) {
            $this->warn('No hay producciones para sincronizar');
            return 0;
        }

        $sincronizados = 0;

        DB::beginTransaction();

        try {
            foreach ($producciones as $produccion) {
                foreach ($produccion->productos as $productoProduccion) {
                    $producto = $productoProduccion->producto;

                    if (!$producto) continue;

                    $existe = Inventario::where('referencia', 'Produccion #' . $produccion->id)
                        ->where('id_producto', $producto->id)
                        ->exists();

                    if (!$existe) {
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $productoProduccion->cantidad,
                            'origen' => 'Produccion Diaria',
                            'referencia' => 'Produccion #' . $produccion->id,
                            'id_usuario' => 1,
                            'fecha_movimiento' => $produccion->fecha,
                            'observacion' => 'Entrada desde produccion - ' . $produccion->responsable,
                        ]);
                        $sincronizados++;
                    }
                }
            }

            DB::commit();
            $this->info("Sincronizacion completada: {$sincronizados} entradas creadas");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}

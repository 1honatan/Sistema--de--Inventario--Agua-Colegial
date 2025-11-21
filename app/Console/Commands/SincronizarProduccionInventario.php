<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Control\ProduccionDiaria;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class SincronizarProduccionInventario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produccion:sincronizar-inventario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza las producciones existentes con el inventario general';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sincronizando producciones con inventario...');

        $producciones = ProduccionDiaria::with('productos.producto')->get();

        if ($producciones->isEmpty()) {
            $this->warn('No hay producciones registradas para sincronizar.');
            return 0;
        }

        $sincronizados = 0;
        $errores = 0;

        DB::beginTransaction();

        try {
            foreach ($producciones as $produccion) {
                foreach ($produccion->productos as $productoProduccion) {
                    $producto = $productoProduccion->producto;

                    if (!$producto) {
                        $this->error("Producto no encontrado para producción #{$produccion->id}");
                        $errores++;
                        continue;
                    }

                    // Verificar si ya existe en inventario
                    $existe = Inventario::where('referencia', 'Producción #' . $produccion->id)
                        ->where('id_producto', $producto->id)
                        ->exists();

                    if (!$existe) {
                        // Crear entrada en inventario
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $productoProduccion->cantidad,
                            'origen' => 'Producción Diaria',
                            'referencia' => 'Producción #' . $produccion->id,
                            'id_usuario' => 1, // Usuario admin por defecto
                            'fecha_movimiento' => $produccion->fecha,
                            'observacion' => 'Entrada automática desde Control de Producción Diaria - Responsable: ' . $produccion->responsable,
                        ]);

                        $sincronizados++;
                        $this->line("✓ Sincronizado: Producción #{$produccion->id} - {$producto->nombre} ({$productoProduccion->cantidad} unidades)");
                    }
                }
            }

            DB::commit();

            $this->info("\n=================================");
            $this->info("Sincronización completada:");
            $this->info("- Entradas creadas: {$sincronizados}");
            if ($errores > 0) {
                $this->warn("- Errores: {$errores}");
            }
            $this->info("=================================");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error durante la sincronización: ' . $e->getMessage());
            return 1;
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\AlertaStock;
use App\Models\Producto;
use Illuminate\Console\Command;

class VerificarStockBajo extends Command
{
    protected $signature = 'verificar:stock-bajo {--umbral=10}';
    protected $description = 'Verificar niveles de stock bajo';

    public function handle(): int
    {
        $umbral = (int) $this->option('umbral');
        $productos = Producto::where('estado', 'activo')->get();

        if ($productos->isEmpty()) {
            $this->warn('No hay productos para verificar');
            return Command::SUCCESS;
        }

        $this->info("Verificando {$productos->count()} productos...");

        $alertas = 0;
        foreach ($productos as $producto) {
            $alerta = AlertaStock::generarSiNecesario($producto, $umbral);
            if ($alerta && $alerta->wasRecentlyCreated) {
                $alertas++;
            }
        }

        $this->info("Verificacion completada. Alertas generadas: {$alertas}");
        return Command::SUCCESS;
    }
}

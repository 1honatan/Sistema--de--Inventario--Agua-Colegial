<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AlertaStock;
use App\Models\Producto;
use Illuminate\Console\Command;

/**
 * Comando Artisan para verificar niveles de stock bajo.
 *
 * Recorre todos los productos activos y genera alertas si el stock
 * está por debajo del umbral mínimo configurado.
 *
 * Uso: php artisan verificar:stock-bajo
 *      php artisan verificar:stock-bajo --umbral=20
 */
class VerificarStockBajo extends Command
{
    /**
     * Nombre y firma del comando.
     *
     * @var string
     */
    protected $signature = 'verificar:stock-bajo
                            {--umbral=10 : Umbral mínimo de stock para generar alerta}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'Verificar niveles de stock bajo y generar alertas automáticamente';

    /**
     * Ejecutar el comando.
     */
    public function handle(): int
    {
        $this->info('🔍 Iniciando verificación de stock bajo...');
        $this->newLine();

        // Obtener umbral de stock desde opciones
        $umbral = (int) $this->option('umbral');
        $verbose = $this->getOutput()->isVerbose();

        // Obtener todos los productos activos
        $productos = Producto::where('estado', 'activo')->get();

        if ($productos->isEmpty()) {
            $this->warn('⚠️  No hay productos activos para verificar.');
            return Command::SUCCESS;
        }

        $this->info("📦 Verificando {$productos->count()} productos...");
        $this->newLine();

        $alertasGeneradas = 0;
        $alertasActualizadas = 0;
        $productosSinProblemas = 0;

        // Crear barra de progreso
        $bar = $this->output->createProgressBar($productos->count());
        $bar->start();

        foreach ($productos as $producto) {
            // Generar alerta si es necesario
            $alerta = AlertaStock::generarSiNecesario($producto, $umbral);

            if ($alerta) {
                if ($alerta->wasRecentlyCreated) {
                    $alertasGeneradas++;

                    if ($verbose) {
                        $this->newLine();
                        $this->warn("⚠️  Nueva alerta: {$producto->nombre} (Stock: {$alerta->cantidad_actual}, Urgencia: {$alerta->nivel_urgencia})");
                    }
                } else {
                    $alertasActualizadas++;

                    if ($verbose) {
                        $this->newLine();
                        $this->info("🔄 Alerta actualizada: {$producto->nombre} (Stock: {$alerta->cantidad_actual})");
                    }
                }
            } else {
                $productosSinProblemas++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Mostrar resumen
        $this->info('✅ Verificación completada');
        $this->newLine();

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Alertas generadas', $alertasGeneradas],
                ['Alertas actualizadas', $alertasActualizadas],
                ['Productos sin problemas', $productosSinProblemas],
                ['Total productos verificados', $productos->count()],
            ]
        );

        // Mostrar alertas críticas
        $alertasCriticas = AlertaStock::activas()
            ->porNivelUrgencia('critica')
            ->with('producto')
            ->get();

        if ($alertasCriticas->isNotEmpty()) {
            $this->newLine();
            $this->error("🚨 {$alertasCriticas->count()} ALERTA(S) CRÍTICA(S) DETECTADA(S):");
            $this->newLine();

            foreach ($alertasCriticas as $alerta) {
                $this->error("  • {$alerta->producto->nombre}: Stock AGOTADO (0 unidades)");
            }
        }

        return Command::SUCCESS;
    }
}

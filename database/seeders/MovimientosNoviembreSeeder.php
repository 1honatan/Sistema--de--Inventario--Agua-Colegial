<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovimientosNoviembreSeeder extends Seeder
{
    /**
     * Generar movimientos de inventario del 1 al 26 de noviembre 2025
     */
    public function run(): void
    {
        echo "🔄 Generando movimientos de inventario del 1 al 26 de noviembre...\n";

        // IDs de productos disponibles
        $productos = DB::table('productos')->pluck('id')->toArray();

        if (empty($productos)) {
            echo "❌ No hay productos en la base de datos\n";
            return;
        }

        $movimientos = [];
        $startDate = Carbon::create(2025, 11, 1, 0, 0, 0);
        $endDate = Carbon::create(2025, 11, 26, 23, 59, 59);

        // Generar movimientos para cada día
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $diaSemana = $date->dayOfWeek;

            // Más movimientos en días laborables (lunes a viernes)
            $cantidadMovimientos = ($diaSemana >= 1 && $diaSemana <= 5) ? rand(8, 15) : rand(3, 8);

            for ($i = 0; $i < $cantidadMovimientos; $i++) {
                $productoId = $productos[array_rand($productos)];
                $tipoMovimiento = (rand(1, 10) <= 6) ? 'entrada' : 'salida'; // 60% entradas, 40% salidas

                // Cantidades realistas según el tipo de producto
                $cantidad = match($productoId) {
                    1 => rand(50, 200),   // Botellones
                    3, 4 => rand(100, 500), // Agua Natural/Saborizada
                    8 => rand(30, 150),    // Hielo
                    6, 9, 10 => rand(20, 100), // Gelatina, Bolos
                    default => rand(10, 100),
                };

                // Hora aleatoria del día
                $hora = rand(6, 20); // Entre 6 AM y 8 PM
                $minuto = rand(0, 59);
                $fechaMovimiento = $date->copy()->setTime($hora, $minuto);

                $movimientos[] = [
                    'id_producto' => $productoId,
                    'tipo_movimiento' => $tipoMovimiento,
                    'cantidad' => $cantidad,
                    'origen' => $tipoMovimiento === 'entrada' ? 'Producción' : 'Inventario',
                    'destino' => $tipoMovimiento === 'entrada' ? 'Inventario' : 'Distribución',
                    'referencia' => $tipoMovimiento === 'entrada'
                        ? 'Producción diaria ' . $date->format('d/m/Y')
                        : 'Salida distribución ' . $date->format('d/m/Y'),
                    'observacion' => null,
                    'id_usuario' => null,
                    'fecha_movimiento' => $fechaMovimiento,
                    'created_at' => $fechaMovimiento,
                    'updated_at' => $fechaMovimiento,
                ];
            }
        }

        // Insertar en lotes
        $totalMovimientos = count($movimientos);
        $chunkSize = 100;
        $chunks = array_chunk($movimientos, $chunkSize);

        echo "📦 Insertando {$totalMovimientos} movimientos en " . count($chunks) . " lotes...\n";

        foreach ($chunks as $index => $chunk) {
            DB::table('inventario')->insert($chunk);
            echo "✅ Lote " . ($index + 1) . "/" . count($chunks) . " insertado\n";
        }

        // Resumen
        $totalEntradas = collect($movimientos)->where('tipo_movimiento', 'entrada')->count();
        $totalSalidas = collect($movimientos)->where('tipo_movimiento', 'salida')->count();
        $cantidadEntradas = collect($movimientos)->where('tipo_movimiento', 'entrada')->sum('cantidad');
        $cantidadSalidas = collect($movimientos)->where('tipo_movimiento', 'salida')->sum('cantidad');

        echo "\n📊 RESUMEN:\n";
        echo "   📥 Entradas: {$totalEntradas} movimientos ({$cantidadEntradas} unidades)\n";
        echo "   📤 Salidas: {$totalSalidas} movimientos ({$cantidadSalidas} unidades)\n";
        echo "   📦 Stock neto: " . ($cantidadEntradas - $cantidadSalidas) . " unidades\n";
        echo "   📅 Periodo: 1 al 26 de noviembre 2025\n";
        echo "\n✅ Movimientos de inventario generados exitosamente!\n";
    }
}

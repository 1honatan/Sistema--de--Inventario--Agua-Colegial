<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProduccionDiciembreSeeder extends Seeder
{
    /**
     * Registrar producciones de diciembre 2025
     * Del 1 al 23 y del 26 al 30 (excluyendo 24 y 25 por vacaciones)
     */
    public function run(): void
    {
        // Responsables rotativos
        $responsables = [
            'Ana Gutierrez - Encargado de Producción',
            'Anderson Aguilar - Encargado de Producción',
            'Helen Aguilar - Operador de Producción',
            'Lidia Canon - Encargado de Producción'
        ];

        // Productos base con sus cantidades promedio (variarán ±20%)
        $productosBase = [
            1 => ['nombre' => 'Botellón 20 Litros', 'cantidad' => 150],      // ID 1
            3 => ['nombre' => 'Agua Natural', 'cantidad' => 1050],           // ID 3
            4 => ['nombre' => 'Agua Saborizada', 'cantidad' => 1000],        // ID 4
            6 => ['nombre' => 'Gelatina', 'cantidad' => 200],                // ID 6
            8 => ['nombre' => 'Hielo en Bolsa 3 kg', 'cantidad' => 70],      // ID 8
            9 => ['nombre' => 'Bolo Grande', 'cantidad' => 400],             // ID 9
            10 => ['nombre' => 'Bolo Pequeño', 'cantidad' => 300],           // ID 10
            11 => ['nombre' => 'Dispenser Unidad', 'cantidad' => 90],        // ID 11
            12 => ['nombre' => 'Agua De Limon', 'cantidad' => 1000],         // ID 12
        ];

        // Fechas a procesar
        $fechas = [];

        // Del 1 al 23 de diciembre
        for ($dia = 1; $dia <= 23; $dia++) {
            $fechas[] = Carbon::create(2025, 12, $dia);
        }

        // Del 26 al 30 de diciembre
        for ($dia = 26; $dia <= 30; $dia++) {
            $fechas[] = Carbon::create(2025, 12, $dia);
        }

        $this->command->info("🏭 Iniciando registro de producción de diciembre 2025...");
        $this->command->info("📅 Días laborables: " . count($fechas) . " días");

        $produccionesCreadas = 0;
        $productosCreados = 0;
        $materialesCreados = 0;
        $movimientosInventario = 0;

        DB::beginTransaction();

        try {
            foreach ($fechas as $index => $fecha) {
                // Seleccionar responsable rotativo
                $responsable = $responsables[$index % count($responsables)];

                // Determinar si es fin de año (después del 20)
                $esFinDeAnio = $fecha->day >= 20;

                // Crear registro maestro de producción
                $produccionId = DB::table('control_produccion_diaria')->insertGetId([
                    'fecha' => $fecha->format('Y-m-d'),
                    'responsable' => $responsable,
                    'observaciones' => $esFinDeAnio
                        ? 'Producción reducida por temporada de fin de año - Baja demanda'
                        : 'Producción normal - Temporada regular',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $produccionesCreadas++;

                $totalBotellones = 0;
                $totalAguas = 0; // Agua Natural + Saborizada + Limón

                // Registrar productos de esta producción
                foreach ($productosBase as $idProducto => $producto) {
                    // Variar cantidad: ±20% para hacer más realista
                    $variacion = rand(-20, 20);

                    // Si es fin de año, reducir más (hasta -40%)
                    if ($esFinDeAnio) {
                        $variacion = rand(-40, -10);
                    }

                    $cantidad = (int) round($producto['cantidad'] * (1 + $variacion / 100));
                    $cantidad = max(10, $cantidad); // Mínimo 10 unidades

                    // Registrar en control_produccion_productos
                    DB::table('control_produccion_productos')->insert([
                        'produccion_id' => $produccionId,
                        'producto_id' => $idProducto,
                        'cantidad' => $cantidad,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $productosCreados++;

                    // Registrar en inventario (entrada automática)
                    DB::table('inventario')->insert([
                        'id_producto' => $idProducto,
                        'tipo_movimiento' => 'entrada',
                        'cantidad' => $cantidad,
                        'origen' => 'Producción',
                        'referencia' => "PROD-{$produccionId}",
                        'fecha_movimiento' => $fecha->format('Y-m-d H:i:s'),
                        'observacion' => "Producción diaria - {$fecha->format('d/m/Y')} - {$responsable}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $movimientosInventario++;

                    // Acumular para materiales
                    if ($idProducto == 1) { // Botellones
                        $totalBotellones += $cantidad;
                    }
                    if (in_array($idProducto, [3, 4, 12])) { // Aguas
                        $totalAguas += $cantidad;
                    }
                }

                // Registrar materiales utilizados

                // 1. Etiquetas para botellones (1 etiqueta = 1 botellón)
                DB::table('control_produccion_materiales')->insert([
                    'produccion_id' => $produccionId,
                    'nombre_material' => 'Etiquetas para Botellones',
                    'cantidad' => $totalBotellones,
                    'unidad_medida' => 'unidades',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $materialesCreados++;

                // 2. Bolsas para empaquetar (1 bolsa = 100 unidades)
                $bolsasNecesarias = (int) ceil($totalAguas / 100);

                DB::table('control_produccion_materiales')->insert([
                    'produccion_id' => $produccionId,
                    'nombre_material' => 'Bolsas para Empaquetar (100 unidades c/u)',
                    'cantidad' => $bolsasNecesarias,
                    'unidad_medida' => 'bolsas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $materialesCreados++;

                $this->command->info("  ✓ {$fecha->format('d/m/Y')} - {$responsable} - {$produccionesCreadas} registros");
            }

            DB::commit();

            $this->command->newLine();
            $this->command->info("✅ Producción de diciembre registrada exitosamente!");
            $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->command->info("📊 RESUMEN:");
            $this->command->info("  • Producciones creadas: {$produccionesCreadas}");
            $this->command->info("  • Productos registrados: {$productosCreados}");
            $this->command->info("  • Materiales registrados: {$materialesCreados}");
            $this->command->info("  • Movimientos de inventario: {$movimientosInventario}");
            $this->command->info("  • Días sin producción: 24 y 25 de diciembre (Navidad)");
            $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Error al registrar producción: " . $e->getMessage());
            throw $e;
        }
    }
}

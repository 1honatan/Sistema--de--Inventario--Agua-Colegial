<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Control\ProduccionDiaria;
use App\Models\Producto;
use App\Models\Personal;

class ProduccionDiariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todo el personal
        $personal = Personal::where('estado', 'activo')->get();

        if ($personal->isEmpty()) {
            $this->command->error('No hay personal registrado en la base de datos.');
            return;
        }

        // Obtener productos necesarios por los nombres exactos en la base de datos
        $productos = [
            'Agua De Limon' => Producto::where('nombre', 'Agua De Limon')->first(),
            'Agua Natural 1L' => Producto::where('nombre', 'Agua Natural 1L')->first(),
            'Agua Saborizada' => Producto::where('nombre', 'Agua Saborizada')->first(),
            'Bolo Grande' => Producto::where('nombre', 'Bolo Grande')->first(),
            'Bolo Pequeño' => Producto::where('nombre', 'Bolo Pequeño')->first(),
            'Gelatina' => Producto::where('nombre', 'Gelatina')->first(),
        ];

        // Verificar que todos los productos existen
        foreach ($productos as $nombre => $producto) {
            if (!$producto) {
                $this->command->warn("Producto '{$nombre}' no encontrado. Se omitirá.");
            }
        }

        // Configurar fechas
        $fechaInicio = Carbon::create(2025, 11, 1);
        $fechaFin = Carbon::create(2025, 11, 21);

        $this->command->info('Generando registros de producción del 1 al 21 de noviembre (excluyendo domingos)...');

        $registrosCreados = 0;

        // Generar registros para cada día (excluyendo domingos)
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            // Saltar domingos (dayOfWeek = 0 es domingo)
            if ($fecha->dayOfWeek === 0) {
                $this->command->info("Saltando domingo: {$fecha->format('Y-m-d')}");
                continue;
            }

            // Seleccionar responsable aleatorio
            $responsable = $personal->random()->nombre_completo;

            DB::beginTransaction();

            try {
                // Crear registro base
                $produccion = ProduccionDiaria::create([
                    'fecha' => $fecha->format('Y-m-d'),
                    'responsable' => $responsable,
                    'turno' => 'Diurno',
                    'preparacion' => 'Normal',
                    'rollos_material' => 0,
                    'gasto_material' => 0.00,
                    'observaciones' => null,
                ]);

                // Agregar productos producidos
                $productosData = [
                    ['nombre' => 'Agua De Limon', 'cantidad' => rand(1200, 1300)],
                    ['nombre' => 'Agua Natural 1L', 'cantidad' => rand(1200, 1300)],
                    ['nombre' => 'Agua Saborizada', 'cantidad' => rand(1200, 1300)],
                    ['nombre' => 'Bolo Grande', 'cantidad' => 200],
                    ['nombre' => 'Bolo Pequeño', 'cantidad' => 200],
                    ['nombre' => 'Gelatina', 'cantidad' => rand(200, 250)],
                ];

                foreach ($productosData as $prodData) {
                    $producto = $productos[$prodData['nombre']] ?? null;
                    if ($producto) {
                        $produccion->productos()->create([
                            'producto_id' => $producto->id,
                            'cantidad' => $prodData['cantidad'],
                        ]);
                    }
                }

                // Agregar materiales utilizados
                $materialesData = [
                    ['nombre' => 'Bolsas de empaquete', 'cantidad' => rand(35, 40)],
                    ['nombre' => 'Botellón de 20L', 'cantidad' => 200],
                    ['nombre' => 'Etiquetas para botellones', 'cantidad' => 200],
                ];

                foreach ($materialesData as $matData) {
                    $produccion->materiales()->create([
                        'nombre_material' => $matData['nombre'],
                        'cantidad' => $matData['cantidad'],
                    ]);
                }

                DB::commit();

                $registrosCreados++;
                $this->command->info("✓ Registro creado para: {$fecha->format('Y-m-d')} - Responsable: {$responsable}");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Error en {$fecha->format('Y-m-d')}: " . $e->getMessage());
            }
        }

        $this->command->info("✅ Total de registros creados: {$registrosCreados}");
    }
}

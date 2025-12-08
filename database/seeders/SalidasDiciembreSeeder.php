<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalidasDiciembreSeeder extends Seeder
{
    /**
     * Registrar salidas (despacho interno) de diciembre 2025
     * Del 1 al 23 y del 26 al 30 (excluyendo 24, 25 y 31)
     */
    public function run(): void
    {
        // Choferes con sus vehículos asignados (cada chofer usa el mismo vehículo todo el mes)
        $choferes = [
            [
                'nombre' => 'deybi aguilar',
                'vehiculo_id' => 2, // TOYOTA Hilux
                'vehiculo_placa' => '1234ABC'
            ],
            [
                'nombre' => 'Sergio Aguilar',
                'vehiculo_id' => 3, // ISUZU D-MAX
                'vehiculo_placa' => '5678DEF'
            ],
            [
                'nombre' => 'Jasmani Aguilar',
                'vehiculo_id' => 4, // NISSAN Frontier
                'vehiculo_placa' => '9012GHI'
            ],
        ];

        // Distribuidores
        $distribuidores = [
            'Antonio Rodriguez',
            'joel buendia',
            'nano alvarez'
        ];

        // Productos base con cantidades (variarán aleatoriamente)
        $productosBase = [
            'agua_limon' => 400,
            'agua_natural' => [500, 650], // Aleatorio entre estos valores
            'agua_saborizada' => 400,
            'bolo_grande' => 200,
            'bolo_peque�o' => 200,
            'botellones' => [40, 50], // Aleatorio
            'dispenser' => 10,
            'gelatina' => 200,
            'hielo' => 40,
        ];

        // Fechas a procesar
        $fechas = [];

        // Del 1 al 23 de diciembre
        for ($dia = 1; $dia <= 23; $dia++) {
            $fechas[] = Carbon::create(2025, 12, $dia);
        }

        // Del 26 al 30 de diciembre (excluir 24, 25, 31)
        for ($dia = 26; $dia <= 30; $dia++) {
            $fechas[] = Carbon::create(2025, 12, $dia);
        }

        $this->command->info("🚚 Iniciando registro de salidas de diciembre 2025...");
        $this->command->info("📅 Días laborables: " . count($fechas) . " días");

        $salidasCreadas = 0;

        DB::beginTransaction();

        try {
            foreach ($fechas as $index => $fecha) {
                // Seleccionar chofer rotativo
                $chofer = $choferes[$index % count($choferes)];

                // Seleccionar distribuidor rotativo
                $distribuidor = $distribuidores[$index % count($distribuidores)];

                // Hora de llegada aleatoria entre 18:00 y 23:50
                $hora = rand(18, 23);
                $minuto = rand(0, 59);
                // Asegurar que no pase de 23:50
                if ($hora === 23 && $minuto > 50) {
                    $minuto = rand(0, 50);
                }
                $horaLlegada = sprintf('%02d:%02d:00', $hora, $minuto);

                // Calcular cantidades de productos (con variación)
                $aguaLimon = rand(350, 450); // ~400 con variación
                $aguaNatural = rand($productosBase['agua_natural'][0], $productosBase['agua_natural'][1]);
                $aguaSaborizada = rand(350, 450); // ~400
                $boloGrande = rand(180, 220); // ~200
                $boloPequeno = rand(180, 220); // ~200
                $botellones = rand($productosBase['botellones'][0], $productosBase['botellones'][1]);
                $dispenser = rand(8, 12); // ~10
                $gelatina = rand(180, 220); // ~200
                $hielo = rand(35, 45); // ~40

                // Calcular retornos (productos no vendidos, aleatorio 0-30 o más por producto)
                $retornoBotellones = rand(0, min(15, (int)($botellones * 0.3))); // Max 30% retorno
                $retornoBoloGrande = rand(0, min(30, (int)($boloGrande * 0.15)));
                $retornoBoloPequeno = rand(0, min(30, (int)($boloPequeno * 0.15)));
                $retornoGelatina = rand(0, min(30, (int)($gelatina * 0.15)));
                $retornoAguaSaborizada = rand(0, min(50, (int)($aguaSaborizada * 0.12)));
                $retornoAguaLimon = rand(0, min(50, (int)($aguaLimon * 0.12)));
                $retornoAguaNatural = rand(0, min(80, (int)($aguaNatural * 0.15)));
                $retornoHielo = rand(0, min(10, (int)($hielo * 0.25)));
                $retornoDispenser = rand(0, min(3, (int)($dispenser * 0.3)));

                $totalRetornos = $retornoBotellones + $retornoBoloGrande + $retornoBoloPequeno +
                                $retornoGelatina + $retornoAguaSaborizada + $retornoAguaLimon +
                                $retornoAguaNatural + $retornoHielo + $retornoDispenser;

                // Crear registro de salida
                DB::table('control_salidas_productos')->insert([
                    'nombre_distribuidor' => $distribuidor,
                    'chofer' => $chofer['nombre'],
                    'tipo_salida' => 'Despacho Interno',
                    'vehiculo_placa' => $chofer['vehiculo_placa'],
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora_llegada' => $horaLlegada,

                    // Productos despachados
                    'botellones' => $botellones,
                    'bolo_grande' => $boloGrande,
                    'bolo_pequeño' => $boloPequeno,
                    'gelatina' => $gelatina,
                    'agua_saborizada' => $aguaSaborizada,
                    'agua_limon' => $aguaLimon,
                    'agua_natural' => $aguaNatural,
                    'hielo' => $hielo,
                    'dispenser' => $dispenser,

                    // Retornos (productos no vendidos)
                    'retorno_botellones' => $retornoBotellones,
                    'retorno_bolo_grande' => $retornoBoloGrande,
                    'retorno_bolo_pequeno' => $retornoBoloPequeno,
                    'retorno_gelatina' => $retornoGelatina,
                    'retorno_agua_saborizada' => $retornoAguaSaborizada,
                    'retorno_agua_limon' => $retornoAguaLimon,
                    'retorno_agua_natural' => $retornoAguaNatural,
                    'retorno_hielo' => $retornoHielo,
                    'retorno_dispenser' => $retornoDispenser,
                    'retornos' => $totalRetornos,

                    // Días de la semana (dejar en 0, no se usan para este tipo de registro)
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0,

                    'choreados' => 0, // Sin productos choreados
                    'observaciones' => sprintf(
                        'Despacho diario - %s - Vendidos: %d unidades',
                        $fecha->format('d/m/Y'),
                        ($botellones + $boloGrande + $boloPequeno + $gelatina +
                         $aguaSaborizada + $aguaLimon + $aguaNatural + $hielo + $dispenser - $totalRetornos)
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $salidasCreadas++;

                $this->command->info(sprintf(
                    "  ✓ %s - %s (%s) - Distribuidor: %s - Llegada: %s - Retornos: %d",
                    $fecha->format('d/m/Y'),
                    $chofer['nombre'],
                    $chofer['vehiculo_placa'],
                    $distribuidor,
                    $horaLlegada,
                    $totalRetornos
                ));
            }

            DB::commit();

            $this->command->newLine();
            $this->command->info("✅ Salidas de diciembre registradas exitosamente!");
            $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->command->info("📊 RESUMEN:");
            $this->command->info("  • Salidas creadas: {$salidasCreadas}");
            $this->command->info("  • Choferes: " . count($choferes));
            $this->command->info("  • Distribuidores: " . count($distribuidores));
            $this->command->info("  • Días sin salidas: 24, 25 y 31 de diciembre");
            $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Error al registrar salidas: " . $e->getMessage());
            throw $e;
        }
    }
}

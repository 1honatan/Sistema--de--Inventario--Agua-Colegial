<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalidasProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Choferes y distribuidores
        $responsables = [
            'deybi aguilar',
            'Jasmani Aguilar',
            'Sergio Aguilar',
        ];

        // Vehículos (excluyendo 1869KLBs)
        $vehiculos = [
            ['placa' => '1234ABC', 'responsable' => 'deybi aguilar'],
            ['placa' => '5678DEF', 'responsable' => 'Sergio Aguilar'],
            ['placa' => '9012GHI', 'responsable' => 'Jasmani Aguilar'],
        ];

        // Configurar fechas
        $fechaInicio = Carbon::create(2025, 11, 1);
        $fechaFin = Carbon::create(2025, 11, 21);

        $this->command->info('Generando registros de salida del 1 al 21 de noviembre (excluyendo domingos)...');

        $registrosCreados = 0;

        // Generar registros para cada día
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            // Saltar domingos (dayOfWeek = 0 es domingo)
            if ($fecha->dayOfWeek === 0) {
                $this->command->info("Saltando domingo: {$fecha->format('Y-m-d')}");
                continue;
            }

            // Crear 3 salidas por día (una por cada vehículo)
            foreach ($vehiculos as $vehiculoData) {
                $responsable = $vehiculoData['responsable'];
                $vehiculo = $vehiculoData['placa'];

                // Generar hora de llegada entre 18:00 y 23:59 (o 00:00)
                $horas = [18, 19, 20, 21, 22, 23, 0];
                $hora = $horas[array_rand($horas)];
                $minutos = rand(0, 59);
                $horaLlegada = sprintf('%02d:%02d:00', $hora, $minutos);

            DB::beginTransaction();

            try {
                // Generar cantidades aleatorias para productos
                $aguaLimon = rand(400, 600) * 10;      // 400-600 bolsas de 10 unidades
                $aguaNatural = rand(400, 600) * 10;    // 400-600 bolsas de 10 unidades
                $aguaSaborizada = rand(400, 600) * 10; // 400-600 bolsas de 10 unidades
                $boloGrande = rand(280, 320);          // ~300 bolos
                $boloPequeno = rand(35, 45);           // ~40 bolsas
                $botellones = rand(40, 50);            // 40 o más
                $gelatina = rand(35, 45);              // ~40 bolsas
                $hielo = rand(25, 35);                 // ~30 bolsas

                // Crear registro de salida
                DB::table('control_salidas_productos')->insert([
                    'nombre_distribuidor' => $responsable,
                    'vehiculo_placa' => $vehiculo,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora_llegada' => $horaLlegada,

                    // Días de la semana (para compatibilidad, todos en 0)
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0,

                    // Productos enviados
                    'agua_limon' => $aguaLimon,
                    'agua_natural' => $aguaNatural,
                    'agua_saborizada' => $aguaSaborizada,
                    'bolo_grande' => $boloGrande,
                    'bolo_pequeño' => $boloPequeno,
                    'botellones' => $botellones,
                    'gelatina' => $gelatina,
                    'hielo' => $hielo,
                    'dispenser' => 0,
                    'choreados' => 0,

                    // Retornos (fijos según especificación)
                    'retornos' => 20 + 10 + 5 + 1 + 3 + 1 + 4 + 3 + 0, // suma total
                    'retorno_agua_limon' => 20,
                    'retorno_agua_natural' => 10,
                    'retorno_agua_saborizada' => 5,
                    'retorno_bolo_grande' => 1,
                    'retorno_bolo_pequeno' => 3,
                    'retorno_botellones' => 1,
                    'retorno_gelatina' => 3,
                    'retorno_hielo' => 0,
                    'retorno_dispenser' => 4,

                    'observaciones' => 'Despacho Interno - Generado automáticamente',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();

                $registrosCreados++;
                $this->command->info("✓ Registro creado para: {$fecha->format('Y-m-d')} - Responsable: {$responsable} - Vehículo: {$vehiculo} - Hora: {$horaLlegada}");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Error en {$fecha->format('Y-m-d')} (Vehículo: {$vehiculo}): " . $e->getMessage());
            }
            } // Fin del foreach de vehículos
        } // Fin del for de fechas

        // Crear entradas de Chorreados en retornos (30 por cada registro)
        $this->command->info('Agregando chorreados a los retornos...');

        DB::table('control_salidas_productos')
            ->whereBetween('fecha', ['2025-11-01', '2025-11-21'])
            ->update(['choreados' => 30]);

        $this->command->info("✅ Total de registros de salida creados: {$registrosCreados}");
        $this->command->info("✅ Chorreados agregados a todos los registros (30 por registro)");
    }
}

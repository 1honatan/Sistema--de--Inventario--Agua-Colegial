<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DespachoInternoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Distribuidores
        $distribuidores = [
            'joel buendia',
            'nano alvarez',
            'Antinio rodriguez'
        ];

        // Choferes disponibles
        $choferes = [
            'deybi aguilar',
            'Jasmani Aguilar',
            'Jhonatan matias pizzo aguilar',
            'José Ramírez',
            'Luis González',
            'Sergio Aguilar'
        ];

        // Vehículos disponibles
        $vehiculos = [
            '1869 KLBS',
            '2547 ABC',
            '3891 XYZ',
            '4562 DEF'
        ];

        // Feriados en Bolivia (noviembre-diciembre 2025)
        $feriados = [
            '2025-11-02', // Día de Todos los Santos
            '2025-12-25', // Navidad
        ];

        // Generar fechas del 1 de noviembre al 30 de diciembre
        $fechaInicio = Carbon::parse('2025-11-01');
        $fechaFin = Carbon::parse('2025-12-30');

        $indiceChofer = 0;
        $indiceVehiculo = 0;

        echo "Generando despachos internos para distribuidores...\n";
        echo "Excluyendo: domingos y feriados (2 nov, 25 dic)\n\n";
        $totalRegistros = 0;
        $diasExcluidos = 0;

        // Para cada fecha
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {

            // Saltar domingos
            if ($fecha->dayOfWeek === Carbon::SUNDAY) {
                $diasExcluidos++;
                continue;
            }

            // Saltar feriados
            if (in_array($fecha->format('Y-m-d'), $feriados)) {
                echo "Saltando feriado: {$fecha->format('d/m/Y')}\n";
                $diasExcluidos++;
                continue;
            }

            // Para cada distribuidor
            foreach ($distribuidores as $distribuidor) {

                // Generar cantidades ALEATORIAS de productos
                $cantidades = $this->generarCantidadesAleatorias();

                // Asignar días de la semana con cantidades aleatorias
                $diasSemana = $this->generarDiasSemana();

                // Rotar chofer y vehículo
                $chofer = $choferes[$indiceChofer % count($choferes)];
                $vehiculo = $vehiculos[$indiceVehiculo % count($vehiculos)];

                // Generar hora de llegada aleatoria
                $horaLlegada = rand(7, 18) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

                // Generar observación
                $observacion = $this->generarObservacion($distribuidor);

                // Preparar datos para insertar
                $datos = [
                    'tipo_salida' => 'Despacho Interno',
                    'nombre_distribuidor' => $distribuidor,
                    'chofer' => $chofer,
                    'vehiculo_placa' => $vehiculo,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora_llegada' => $horaLlegada,

                    // Días de la semana (cantidades por día)
                    'lunes' => $diasSemana['lunes'],
                    'martes' => $diasSemana['martes'],
                    'miercoles' => $diasSemana['miercoles'],
                    'jueves' => $diasSemana['jueves'],
                    'viernes' => $diasSemana['viernes'],
                    'sabado' => $diasSemana['sabado'],
                    'domingo' => $diasSemana['domingo'],

                    // Productos enviados (ALEATORIOS)
                    'botellones' => $cantidades['botellones'],
                    'agua_natural' => $cantidades['agua_natural'],
                    'agua_saborizada' => $cantidades['agua_saborizada'],
                    'agua_limon' => $cantidades['agua_limon'],
                    'hielo' => $cantidades['hielo'],
                    'bolo_grande' => $cantidades['bolo_grande'],
                    'bolo_pequeño' => $cantidades['bolo_pequeno'],
                    'gelatina' => $cantidades['gelatina'],
                    'dispenser' => 0,
                    'choreados' => 0,

                    // Retornos (todos en 0 para despacho interno)
                    'retornos' => 0,
                    'retorno_botellones' => 0,
                    'retorno_bolo_grande' => 0,
                    'retorno_bolo_pequeno' => 0,
                    'retorno_gelatina' => 0,
                    'retorno_agua_saborizada' => 0,
                    'retorno_agua_limon' => 0,
                    'retorno_agua_natural' => 0,
                    'retorno_hielo' => 0,
                    'retorno_dispenser' => 0,

                    // Campos de cliente (vacíos para despacho interno)
                    'nombre_cliente' => null,
                    'direccion_entrega' => null,
                    'telefono_cliente' => null,

                    'observaciones' => $observacion,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insertar en la base de datos
                DB::table('control_salidas_productos')->insert($datos);

                $totalRegistros++;

                // Rotar índices
                $indiceChofer++;
                $indiceVehiculo++;
            }

            // Mostrar progreso cada 7 días
            if ($fecha->day % 7 == 0) {
                echo "Procesado hasta: {$fecha->format('d/m/Y')} - Total registros: {$totalRegistros}\n";
            }
        }

        echo "\n✓ Seeder completado exitosamente!\n";
        echo "Total de despachos internos creados: {$totalRegistros}\n";
        echo "Días excluidos (domingos + feriados): {$diasExcluidos}\n";
        echo "Periodo: 01/11/2025 - 30/12/2025\n";
        echo "Distribuidores: " . count($distribuidores) . "\n";
    }

    /**
     * Generar cantidades aleatorias de productos
     */
    private function generarCantidadesAleatorias(): array
    {
        return [
            'botellones' => rand(50, 200),
            'agua_natural' => rand(200, 500),
            'agua_saborizada' => rand(100, 300),
            'agua_limon' => rand(300, 600),
            'hielo' => rand(50, 150),
            'bolo_grande' => rand(20, 80),
            'bolo_pequeno' => rand(30, 100),
            'gelatina' => rand(40, 120),
        ];
    }

    /**
     * Generar cantidades para días de la semana
     */
    private function generarDiasSemana(): array
    {
        return [
            'lunes' => rand(10, 50),
            'martes' => rand(10, 50),
            'miercoles' => rand(10, 50),
            'jueves' => rand(10, 50),
            'viernes' => rand(10, 50),
            'sabado' => rand(5, 30),
            'domingo' => 0, // No trabajan domingos
        ];
    }

    /**
     * Generar observación según el distribuidor
     */
    private function generarObservacion($distribuidor): string
    {
        $observaciones = [
            'joel buendia' => 'Zona norte y este - Ruta completa',
            'nano alvarez' => 'Zona sur y oeste - Ruta completa',
            'Antinio rodriguez' => 'Zona centro - Ruta completa',
        ];

        return $observaciones[$distribuidor] ?? 'Despacho normal - Ruta asignada';
    }
}

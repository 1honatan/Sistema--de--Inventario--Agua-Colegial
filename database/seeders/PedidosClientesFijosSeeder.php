<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidosClientesFijosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clientes fijos con sus configuraciones
        $clientesFijos = [
            [
                'nombre' => 'Surtidor Cotapachi Quillacollo',
                'direccion' => 'Av. Principal Cotapachi, Quillacollo',
                'chofer_fijo' => 'Jhonatan matias pizzo aguilar',
                'productos' => true,
            ],
            [
                'nombre' => 'Surtidor Villa Moderna Quillacollo',
                'direccion' => 'Zona Villa Moderna, Quillacollo',
                'chofer_fijo' => 'Jhonatan matias pizzo aguilar',
                'productos' => true,
            ],
            [
                'nombre' => 'Colegio Edgar Montaño',
                'direccion' => 'Av. Educación, Quillacollo',
                'chofer_fijo' => 'Jhonatan matias pizzo aguilar',
                'productos' => true,
            ],
            [
                'nombre' => 'Condominio Tapayeka',
                'direccion' => 'Condominio Tapayeka, Quillacollo',
                'chofer_fijo' => null, // Rotativo
                'productos' => true,
            ],
            [
                'nombre' => 'Condominio Tiquipaya',
                'direccion' => 'Condominio Residencial, Tiquipaya',
                'chofer_fijo' => null, // Rotativo
                'productos' => true,
            ],
            [
                'nombre' => 'Hielero Montaña',
                'direccion' => 'Zona Montaña, Quillacollo',
                'chofer_fijo' => 'Jhonatan matias pizzo aguilar',
                'productos' => true,
            ],
            [
                'nombre' => 'Imprenta',
                'direccion' => 'Zona Centro, Quillacollo',
                'chofer_fijo' => 'Jhonatan matias pizzo aguilar',
                'productos' => false, // Solo botellones
            ],
        ];

        // Distribuidores disponibles
        $distribuidores = ['joel buendia', 'nano alvarez', 'Antinio rodriguez'];

        // Choferes disponibles para rotación
        $choferesRotativos = ['deybi aguilar', 'Jasmani Aguilar', 'José Ramírez', 'Luis González', 'Sergio Aguilar'];

        // Vehículo fijo para pedidos
        $vehiculoFijo = '1869 KLBS';

        // Feriados en Bolivia (noviembre-diciembre 2025)
        $feriados = [
            '2025-11-02', // Día de Todos los Santos
            '2025-12-25', // Navidad
        ];

        // Generar fechas del 1 de noviembre al 30 de diciembre
        $fechaInicio = Carbon::parse('2025-11-01');
        $fechaFin = Carbon::parse('2025-12-30');

        $indiceDist = 0;
        $indiceChofer = 0;

        echo "Generando pedidos de clientes fijos...\n";
        echo "Patrón: Un día SÍ, un día NO (intercalado)\n";
        echo "Excluyendo: domingos y feriados (2 nov, 25 dic)\n\n";
        $totalRegistros = 0;
        $diasExcluidos = 0;
        $contadorDias = 0; // Para patrón intercalado

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

            // Patrón intercalado: un día sí, un día no
            $contadorDias++;
            if ($contadorDias % 2 == 0) {
                // Día NO - saltar
                continue;
            }

            // Para cada cliente fijo
            foreach ($clientesFijos as $cliente) {

                // Generar cantidades ALEATORIAS de productos
                $cantidades = $this->generarCantidadesAleatorias($cliente['productos']);

                // Determinar chofer (fijo o rotativo)
                $chofer = $cliente['chofer_fijo'] ?? $choferesRotativos[$indiceChofer % count($choferesRotativos)];

                // Rotar distribuidor
                $distribuidor = $distribuidores[$indiceDist % count($distribuidores)];

                // Generar teléfono aleatorio
                $telefono = '7' . rand(1000000, 9999999);

                // Generar observación
                $observacion = "Tel: {$telefono} - " . $this->generarObservacionUbicacion($cliente['nombre']);

                // Preparar datos para insertar
                $datos = [
                    'tipo_salida' => 'Pedido Cliente',
                    'nombre_cliente' => $cliente['nombre'],
                    'direccion_entrega' => $cliente['direccion'],
                    'telefono_cliente' => $telefono,
                    'chofer' => $chofer,
                    'nombre_distribuidor' => $distribuidor,
                    'vehiculo_placa' => $vehiculoFijo,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora_llegada' => rand(8, 17) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),

                    // Productos enviados (ALEATORIOS)
                    'botellones' => $cantidades['botellones'],
                    'agua_natural' => $cantidades['agua_natural'],
                    'agua_saborizada' => $cantidades['agua_saborizada'],
                    'agua_limon' => $cantidades['agua_limon'],
                    'hielo' => $cantidades['hielo'],
                    'bolo_grande' => 0,
                    'bolo_pequeño' => 0,
                    'gelatina' => 0,
                    'dispenser' => 0,
                    'choreados' => 0,

                    // Días de la semana (todos en 0 para pedidos)
                    'lunes' => 0,
                    'martes' => 0,
                    'miercoles' => 0,
                    'jueves' => 0,
                    'viernes' => 0,
                    'sabado' => 0,
                    'domingo' => 0,

                    // Retornos (todos en 0)
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

                    'observaciones' => $observacion,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insertar en la base de datos
                DB::table('control_salidas_productos')->insert($datos);

                $totalRegistros++;

                // Rotar índices
                $indiceDist++;
                $indiceChofer++;
            }

            // Mostrar progreso cada 7 días
            if ($fecha->day % 7 == 0) {
                echo "Procesado hasta: {$fecha->format('d/m/Y')} - Total registros: {$totalRegistros}\n";
            }
        }

        echo "\n✓ Seeder completado exitosamente!\n";
        echo "Total de pedidos creados: {$totalRegistros}\n";
        echo "Días excluidos (domingos + feriados): {$diasExcluidos}\n";
        echo "Periodo: 01/11/2025 - 30/12/2025\n";
        echo "Clientes fijos: " . count($clientesFijos) . "\n";
    }

    /**
     * Generar cantidades aleatorias de productos
     */
    private function generarCantidadesAleatorias($todosLosProductos): array
    {
        if (!$todosLosProductos) {
            // Solo botellones para Imprenta (cantidad aleatoria)
            return [
                'botellones' => rand(10, 40),
                'agua_natural' => 0,
                'agua_saborizada' => 0,
                'agua_limon' => 0,
                'hielo' => 0,
            ];
        }

        // Cantidades aleatorias para todos los productos
        return [
            'botellones' => rand(10, 50),
            'agua_natural' => rand(100, 250),
            'agua_saborizada' => rand(30, 100),
            'agua_limon' => rand(150, 300),
            'hielo' => rand(20, 50),
        ];
    }

    /**
     * Generar observación según el tipo de cliente
     */
    private function generarObservacionUbicacion($nombreCliente): string
    {
        $observaciones = [
            'Surtidor Cotapachi Quillacollo' => 'Casa de color verde, portón negro, junto al mercado',
            'Surtidor Villa Moderna Quillacollo' => 'Edificio blanco, 2do piso, al frente de la plaza',
            'Colegio Edgar Montaño' => 'Ingresar por portón principal, entregar en secretaría',
            'Condominio Tapayeka' => 'Torre B, Depto 304, interfono color gris',
            'Condominio Tiquipaya' => 'Entrada principal, casa #15, portón café',
            'Hielero Montaña' => 'Local comercial esquina, letrero azul HIELOS',
            'Imprenta' => 'Puerta roja, letrero IMPRENTA, tocar timbre',
        ];

        return $observaciones[$nombreCliente] ?? 'Entregar en recepción';
    }
}

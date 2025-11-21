<?php
// Script para generar registros de asistencia con fechas anteriores

$host = '127.0.0.1';
$port = '3307';
$dbname = 'agua_colegial_bd';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener personal activo
    $stmt = $pdo->query("SELECT id, nombre_completo, cargo FROM personal WHERE estado = 'activo'");
    $personal = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Definir horarios según cargo
    // Producción: 6:00 - 17:00 (puede extenderse un poco)
    // Chofer/Distribuidor: 6:00 - 20:00 (puede llegar hasta 1:00 am)

    $produccionCargos = ['Operador de Producción', 'Encargado de Producción', 'Supervisor'];
    $distribucionCargos = ['Chofer', 'Distribuidor'];

    // Estados posibles
    $estados = ['presente', 'presente', 'presente', 'presente', 'tardanza', 'permiso'];

    // Días de la semana
    $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    // Generar registros del 1 al 18 de noviembre (lunes a sábado, sin domingos)
    $fechaInicio = new DateTime('2025-11-01');
    $fechaFin = new DateTime('2025-11-18');

    $insertados = 0;

    // Limpiar registros anteriores si existen
    $pdo->exec("DELETE FROM asistencias_semanales WHERE fecha >= '2025-11-01' AND fecha <= '2025-11-18'");

    $interval = new DateInterval('P1D');
    $periodo = new DatePeriod($fechaInicio, $interval, $fechaFin->modify('+1 day'));

    foreach ($periodo as $fecha) {
        $fechaStr = $fecha->format('Y-m-d');
        $diaSemana = $fecha->format('N'); // 1 = Lunes, 7 = Domingo
        $nombreDia = $diasSemana[$diaSemana - 1];

        // Saltar domingos completamente
        if ($diaSemana == 7) {
            continue;
        }

        foreach ($personal as $empleado) {
            // Todos los empleados trabajan de lunes a sábado

            $cargo = $empleado['cargo'];
            $personalId = $empleado['id'];

            // Determinar horarios según cargo
            if (in_array($cargo, $produccionCargos)) {
                // Producción: 6:00 AM - 17:00 PM (puede variar)
                $entradaBase = '06:00';

                // Variación en salida (16:30 a 18:00)
                $minutosSalida = rand(990, 1080); // 16:30 a 18:00
                $horaSalida = sprintf('%02d:%02d', floor($minutosSalida / 60), $minutosSalida % 60);

            } else {
                // Chofer/Distribuidor: 6:00 AM - 20:00 PM o más tarde
                $entradaBase = '06:00';

                // Variación en salida (18:00 a 01:00 del día siguiente)
                $opciones = [
                    rand(1080, 1200), // 18:00 - 20:00
                    rand(1200, 1320), // 20:00 - 22:00
                    rand(1320, 1440), // 22:00 - 00:00
                    rand(1440, 1500), // 00:00 - 01:00
                ];
                $minutosSalida = $opciones[array_rand($opciones)];

                if ($minutosSalida >= 1440) {
                    $minutosSalida -= 1440;
                    $horaSalida = sprintf('%02d:%02d', floor($minutosSalida / 60), $minutosSalida % 60);
                } else {
                    $horaSalida = sprintf('%02d:%02d', floor($minutosSalida / 60), $minutosSalida % 60);
                }
            }

            // Determinar estado
            $estado = $estados[array_rand($estados)];

            // Si es tardanza, ajustar hora de entrada
            $horaEntrada = $entradaBase;
            if ($estado == 'tardanza') {
                $minutosRetraso = rand(10, 45);
                $horaEntrada = sprintf('06:%02d', $minutosRetraso);
            } elseif ($estado == 'permiso') {
                // Si tiene permiso, puede que no trabaje o trabaje medio día
                if (rand(0, 1) == 0) {
                    continue; // No trabaja
                }
                // Trabaja medio día
                $horaEntrada = '06:00';
                $horaSalida = '12:00';
            }

            // Observaciones variadas
            $observaciones = [
                'Registro normal',
                'Sin novedad',
                'Turno completo',
                '',
                'Buen desempeño',
                ''
            ];

            if ($estado == 'tardanza') {
                $observaciones = [
                    'Llegó tarde por tráfico',
                    'Retraso justificado',
                    'Problemas de transporte',
                    'Tardanza leve'
                ];
            } elseif ($estado == 'permiso') {
                $observaciones = [
                    'Permiso por cita médica',
                    'Permiso personal',
                    'Diligencia familiar',
                    'Medio día de permiso'
                ];
            }

            $observacion = $observaciones[array_rand($observaciones)];

            // Insertar registro
            $sql = "INSERT INTO asistencias_semanales
                    (personal_id, fecha, dia_semana, entrada_hora, salida_hora, estado, observaciones, created_at, updated_at)
                    VALUES
                    (:personal_id, :fecha, :dia_semana, :entrada, :salida, :estado, :obs, NOW(), NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':personal_id' => $personalId,
                ':fecha' => $fechaStr,
                ':dia_semana' => $nombreDia,
                ':entrada' => $horaEntrada,
                ':salida' => $horaSalida,
                ':estado' => $estado,
                ':obs' => $observacion
            ]);

            $insertados++;
        }
    }

    echo "Se insertaron $insertados registros de asistencia.\n";

    // Mostrar resumen por estado
    $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM asistencias_semanales WHERE fecha >= '2025-11-01' GROUP BY estado");
    echo "\nResumen por estado:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['estado']}: {$row['total']}\n";
    }

    // Mostrar algunos ejemplos
    echo "\nEjemplos de registros:\n";
    $stmt = $pdo->query("
        SELECT a.fecha, p.nombre_completo, p.cargo, a.entrada_hora, a.salida_hora, a.estado
        FROM asistencias_semanales a
        JOIN personal p ON a.personal_id = p.id
        WHERE a.fecha >= '2025-11-10'
        ORDER BY a.fecha DESC, p.cargo, p.nombre_completo
        LIMIT 20
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['fecha']} | {$row['nombre_completo']} ({$row['cargo']}) | {$row['entrada_hora']} - {$row['salida_hora']} | {$row['estado']}\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

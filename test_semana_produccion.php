<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Control\SalidaProducto;
use Carbon\Carbon;

echo "Testing producción query with semana=-1\n";
$semana = -1;
$inicioSemana = Carbon::now()->addWeeks($semana)->startOfWeek();
$finSemana = Carbon::now()->addWeeks($semana)->endOfWeek();

echo "Hoy: " . Carbon::now()->format('Y-m-d') . "\n";
echo "Inicio semana -1: " . $inicioSemana->format('Y-m-d') . "\n";
echo "Fin semana -1: " . $finSemana->format('Y-m-d') . "\n\n";

// Verificar cuántos registros hay en este rango
$count = SalidaProducto::whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
    ->count();

echo "Total registros en semana -1: {$count}\n\n";

if ($count > 0) {
    $salidas = SalidaProducto::whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
        ->orderBy('fecha', 'desc')
        ->limit(5)
        ->get();

    echo "Primeros 5 registros:\n";
    foreach ($salidas as $salida) {
        echo "ID: {$salida->id}, Fecha: {$salida->fecha}, Distribuidor: {$salida->nombre_distribuidor}\n";
    }
}

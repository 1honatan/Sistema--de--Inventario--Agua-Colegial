<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Salidas de Productos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #0284c7; padding-bottom: 10px; }
        .header h1 { color: #0284c7; font-size: 18px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 9px; }
        .info-section { background-color: #f0f9ff; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 5px; }
        .info-label { font-weight: bold; color: #0284c7; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 8px; }
        table thead { background-color: #0284c7; color: white; }
        table th { padding: 6px 4px; text-align: center; font-weight: bold; font-size: 8px; border-right: 1px solid #fff; }
        table td { padding: 4px; border-bottom: 1px solid #e5e7eb; text-align: center; font-size: 8px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .tipo-despacho { background-color: #dbeafe; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 7px; }
        .tipo-pedido { background-color: #fef3c7; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 7px; }
        .tipo-venta { background-color: #dcfce7; padding: 2px 6px; border-radius: 3px; font-weight: bold; font-size: 7px; }
        .summary { background-color: #dbeafe; padding: 12px; border-radius: 5px; margin-top: 15px; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .summary-item { text-align: center; }
        .summary-label { font-weight: bold; color: #0284c7; font-size: 8px; display: block; margin-bottom: 3px; }
        .summary-value { font-size: 12px; font-weight: bold; color: #0369a1; }
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 2px solid #e5e7eb; font-size: 8px; color: #666; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE SALIDAS DE PRODUCTOS</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #0284c7; margin-bottom: 8px; font-size: 11px;">Período del Reporte</h3>
        <div class="info-row">
            <span class="info-label">Fecha Inicio:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_inicio'])->format('d/m/Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Fecha Fin:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_fin'])->format('d/m/Y') }}
        </div>
        @if(!empty($validado['tipo_salida']))
        <div class="info-row">
            <span class="info-label">Tipo de Salida:</span>
            {{ $validado['tipo_salida'] }}
        </div>
        @else
        <div class="info-row">
            <span class="info-label">Tipo de Salida:</span>
            Todos los tipos
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 6%;">Fecha</th>
                <th style="width: 8%;">Tipo</th>
                <th style="width: 11%;">Cliente/Distribuidor</th>
                <th style="width: 9%;">Chofer</th>
                <th style="width: 7%;">Vehículo</th>
                <th style="width: 5%;">Bot. 20L</th>
                <th style="width: 5%;">Agua Nat.</th>
                <th style="width: 5%;">Agua Lim.</th>
                <th style="width: 5%;">Agua Sab.</th>
                <th style="width: 5%;">Hielo</th>
                <th style="width: 5%;">B.Grande</th>
                <th style="width: 5%;">B.Peq.</th>
                <th style="width: 5%;">Gelatina</th>
                <th style="width: 5%;">Ret.</th>
                <th style="width: 6%;">Hora</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totales = [
                    'botellones' => 0,
                    'agua_natural' => 0,
                    'agua_limon' => 0,
                    'agua_saborizada' => 0,
                    'hielo' => 0,
                    'bolo_grande' => 0,
                    'bolo_pequeno' => 0,
                    'gelatina' => 0,
                    'retornos' => 0,
                ];
            @endphp
            @forelse($salidas as $salida)
                @php
                    $totales['botellones'] += $salida->botellones ?? 0;
                    $totales['agua_natural'] += $salida->agua_natural ?? 0;
                    $totales['agua_limon'] += $salida->agua_limon ?? 0;
                    $totales['agua_saborizada'] += $salida->agua_saborizada ?? 0;
                    $totales['hielo'] += $salida->hielo ?? 0;
                    $totales['bolo_grande'] += $salida->bolo_grande ?? 0;
                    $totales['bolo_pequeno'] += $salida->bolo_pequeño ?? 0;
                    $totales['gelatina'] += $salida->gelatina ?? 0;
                    $totales['retornos'] += $salida->retornos ?? 0;

                    $tipoClass = 'tipo-despacho';
                    $tipoAbrev = 'DI';
                    if ($salida->tipo_salida === 'Pedido Cliente') {
                        $tipoClass = 'tipo-pedido';
                        $tipoAbrev = 'PC';
                    } elseif ($salida->tipo_salida === 'Venta Directa') {
                        $tipoClass = 'tipo-venta';
                        $tipoAbrev = 'VD';
                    }

                    $nombreMostrar = $salida->nombre_distribuidor;
                    if ($salida->tipo_salida === 'Pedido Cliente' && $salida->nombre_cliente) {
                        $nombreMostrar = $salida->nombre_cliente;
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}</td>
                    <td><span class="{{ $tipoClass }}">{{ $tipoAbrev }}</span></td>
                    <td class="text-left" style="padding-left: 6px;">{{ $nombreMostrar }}</td>
                    <td class="text-left" style="padding-left: 6px;">{{ $salida->chofer ?? '-' }}</td>
                    <td>{{ $salida->vehiculo_placa ?? '-' }}</td>
                    <td class="text-right">{{ $salida->botellones > 0 ? number_format($salida->botellones) : '-' }}</td>
                    <td class="text-right">{{ $salida->agua_natural > 0 ? number_format($salida->agua_natural) : '-' }}</td>
                    <td class="text-right">{{ $salida->agua_limon > 0 ? number_format($salida->agua_limon) : '-' }}</td>
                    <td class="text-right">{{ $salida->agua_saborizada > 0 ? number_format($salida->agua_saborizada) : '-' }}</td>
                    <td class="text-right">{{ $salida->hielo > 0 ? number_format($salida->hielo) : '-' }}</td>
                    <td class="text-right">{{ $salida->bolo_grande > 0 ? number_format($salida->bolo_grande) : '-' }}</td>
                    <td class="text-right">{{ $salida->bolo_pequeño > 0 ? number_format($salida->bolo_pequeño) : '-' }}</td>
                    <td class="text-right">{{ $salida->gelatina > 0 ? number_format($salida->gelatina) : '-' }}</td>
                    <td class="text-right">{{ $salida->retornos > 0 ? number_format($salida->retornos) : '-' }}</td>
                    <td>{{ $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de salidas para el período seleccionado
                    </td>
                </tr>
            @endforelse

            @if($salidas->count() > 0)
            <tr style="background-color: #dbeafe; font-weight: bold;">
                <td colspan="5" class="text-right" style="padding-right: 10px;">TOTALES:</td>
                <td class="text-right">{{ number_format($totales['botellones']) }}</td>
                <td class="text-right">{{ number_format($totales['agua_natural']) }}</td>
                <td class="text-right">{{ number_format($totales['agua_limon']) }}</td>
                <td class="text-right">{{ number_format($totales['agua_saborizada']) }}</td>
                <td class="text-right">{{ number_format($totales['hielo']) }}</td>
                <td class="text-right">{{ number_format($totales['bolo_grande']) }}</td>
                <td class="text-right">{{ number_format($totales['bolo_pequeno']) }}</td>
                <td class="text-right">{{ number_format($totales['gelatina']) }}</td>
                <td class="text-right">{{ number_format($totales['retornos']) }}</td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #0284c7; margin-bottom: 12px; font-size: 11px;">Resumen por Producto</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Botellones 20L</span>
                <span class="summary-value">{{ number_format($totales['botellones']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Agua Natural</span>
                <span class="summary-value">{{ number_format($totales['agua_natural']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Agua Limón</span>
                <span class="summary-value">{{ number_format($totales['agua_limon']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Agua Saborizada</span>
                <span class="summary-value">{{ number_format($totales['agua_saborizada']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Hielo</span>
                <span class="summary-value">{{ number_format($totales['hielo']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Bolo Grande</span>
                <span class="summary-value">{{ number_format($totales['bolo_grande']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Bolo Pequeño</span>
                <span class="summary-value">{{ number_format($totales['bolo_pequeno']) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Gelatina</span>
                <span class="summary-value">{{ number_format($totales['gelatina']) }}</span>
            </div>
        </div>
        <div style="margin-top: 15px; padding-top: 12px; border-top: 2px solid #0284c7;">
            <div class="summary-item" style="display: inline-block; width: 48%;">
                <span class="summary-label">Total de Salidas</span>
                <span class="summary-value">{{ number_format($totalRegistros) }}</span>
            </div>
            <div class="summary-item" style="display: inline-block; width: 48%;">
                <span class="summary-label">Total Retornos</span>
                <span class="summary-value">{{ number_format($totales['retornos']) }}</span>
            </div>
        </div>
    </div>

    <div style="margin-top: 15px; font-size: 8px; padding: 8px; background-color: #f9fafb; border-radius: 5px;">
        <p style="margin-bottom: 5px;"><strong>Leyenda de Tipos:</strong></p>
        <p><span class="tipo-despacho">DI</span> = Despacho Interno &nbsp;&nbsp; <span class="tipo-pedido">PC</span> = Pedido Cliente &nbsp;&nbsp; <span class="tipo-venta">VD</span> = Venta Directa</p>
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Movimientos de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #16a34a;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #16a34a;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 10px;
        }

        .info-section {
            background-color: #f0fdf4;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #16a34a;
        }

        .info-row {
            display: inline-block;
            width: 32%;
            margin-bottom: 6px;
        }

        .info-label {
            font-weight: bold;
            color: #16a34a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #16a34a;
            color: white;
        }

        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        table td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .badge-entrada {
            background-color: #dcfce7;
            color: #166534;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }

        .badge-salida {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }

        .summary {
            background-color: #dcfce7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .summary-item {
            display: inline-block;
            width: 48%;
            margin-bottom: 5px;
        }

        .summary-label {
            font-weight: bold;
            color: #16a34a;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #166534;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>REPORTE DE MOVIMIENTOS DE INVENTARIO</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Filtros Aplicados --}}
    <div class="info-section">
        <h3 style="color: #16a34a; margin-bottom: 10px; font-size: 13px;">Filtros Aplicados</h3>

        @if(!empty($validado['fecha_inicio']))
        <div class="info-row">
            <span class="info-label">Fecha Inicio:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_inicio'])->format('d/m/Y') }}
        </div>
        @endif

        @if(!empty($validado['fecha_fin']))
        <div class="info-row">
            <span class="info-label">Fecha Fin:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_fin'])->format('d/m/Y') }}
        </div>
        @endif

        @if(!empty($validado['tipo_movimiento']))
        <div class="info-row">
            <span class="info-label">Tipo Movimiento:</span>
            {{ ucfirst($validado['tipo_movimiento']) . 's' }}
        </div>
        @endif
    </div>

    {{-- Tabla de Movimientos --}}
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 8%;">Tipo</th>
                <th style="width: 22%;">Producto</th>
                <th style="width: 10%; text-align: right;">Cantidad</th>
                <th style="width: 15%;">Origen</th>
                <th style="width: 35%;">Observación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge-{{ $mov->tipo_movimiento }}">
                            {{ ucfirst($mov->tipo_movimiento) }}
                        </span>
                    </td>
                    <td>{{ $mov->producto_nombre }}</td>
                    <td style="text-align: right; font-weight: bold;">
                        {{ number_format($mov->cantidad) }} {{ $mov->unidad_medida }}
                    </td>
                    <td>{{ $mov->origen ?? '-' }}</td>
                    <td style="font-size: 9px;">{{ $mov->observacion ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        No hay movimientos de inventario para los filtros seleccionados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Resumen --}}
    @if($movimientos->count() > 0)
    <div class="summary">
        <h3 style="color: #16a34a; margin-bottom: 15px;">Resumen de Movimientos</h3>

        <div class="summary-item">
            <span class="summary-label">Total de Movimientos:</span><br>
            <span class="summary-value">{{ number_format($movimientos->count()) }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Cantidad Total:</span><br>
            <span class="summary-value">{{ number_format($movimientos->sum('cantidad')) }} unidades</span>
        </div>

        @if(empty($validado['tipo_movimiento']))
        <div class="summary-item">
            <span class="summary-label">Entradas:</span><br>
            <span class="summary-value" style="color: #16a34a;">
                {{ number_format($movimientos->where('tipo_movimiento', 'entrada')->count()) }}
            </span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Salidas:</span><br>
            <span class="summary-value" style="color: #dc2626;">
                {{ number_format($movimientos->where('tipo_movimiento', 'salida')->count()) }}
            </span>
        </div>
        @endif
    </div>
    @endif

    {{-- Pie de página --}}
    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>

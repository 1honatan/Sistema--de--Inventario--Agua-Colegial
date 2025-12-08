<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #16a34a;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #16a34a;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11px;
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
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .stock-bajo {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: bold;
        }

        .stock-normal {
            color: #166534;
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
            font-size: 16px;
            font-weight: bold;
            color: #166534;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>REPORTE DE INVENTARIO</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- Filtros Aplicados --}}
    @if(isset($validado) && (!empty($validado['fecha_inicio']) || !empty($validado['fecha_fin']) || !empty($validado['tipo_movimiento'])))
    <div style="background-color: #f0fdf4; padding: 12px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #16a34a;">
        <h3 style="color: #16a34a; margin-bottom: 10px; font-size: 14px;">Filtros Aplicados</h3>

        @if(!empty($validado['fecha_inicio']))
        <div style="display: inline-block; width: 48%; margin-bottom: 6px;">
            <span style="font-weight: bold; color: #16a34a;">Fecha Inicio:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_inicio'])->format('d/m/Y') }}
        </div>
        @endif

        @if(!empty($validado['fecha_fin']))
        <div style="display: inline-block; width: 48%; margin-bottom: 6px;">
            <span style="font-weight: bold; color: #16a34a;">Fecha Fin:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_fin'])->format('d/m/Y') }}
        </div>
        @endif

        @if(!empty($validado['tipo_movimiento']))
        <div style="display: inline-block; width: 48%; margin-bottom: 6px;">
            <span style="font-weight: bold; color: #16a34a;">Tipo Movimiento:</span>
            {{ ucfirst($validado['tipo_movimiento']) }}
        </div>
        @endif
    </div>
    @endif

    {{-- Tabla de Inventario --}}
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Descripción</th>
                <th style="text-align: center;">Stock Actual</th>
                <th style="text-align: center;">Unidad</th>
                <th style="text-align: center;">Stock Mínimo</th>
                <th style="text-align: center;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventario as $item)
                @php
                    $stockBajo = $item['stock_actual'] < $item['stock_minimo'];
                @endphp
                <tr>
                    <td>{{ $item['producto']->nombre }}</td>
                    <td>{{ $item['producto']->descripcion ?? 'Sin descripción' }}</td>
                    <td style="text-align: center;" class="{{ $stockBajo ? 'stock-bajo' : 'stock-normal' }}">
                        {{ number_format($item['stock_actual']) }}
                    </td>
                    <td style="text-align: center;">{{ $item['producto']->unidad_medida ?? 'Unidad' }}</td>
                    <td style="text-align: center;">{{ number_format($item['stock_minimo']) }}</td>
                    <td style="text-align: center;">
                        @if($stockBajo)
                            <span style="color: #dc2626; font-weight: bold;">BAJO</span>
                        @else
                            <span style="color: #16a34a; font-weight: bold;">OK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de inventario
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Resumen --}}
    <div class="summary">
        <h3 style="color: #16a34a; margin-bottom: 15px;">Resumen del Inventario</h3>

        <div class="summary-item">
            <span class="summary-label">Total de Productos:</span><br>
            <span class="summary-value">{{ $inventario->count() }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Productos con Stock Bajo:</span><br>
            <span class="summary-value" style="color: #dc2626;">
                {{ $inventario->filter(fn($item) => $item['stock_actual'] < $item['stock_minimo'])->count() }}
            </span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Stock Total:</span><br>
            <span class="summary-value">{{ number_format($inventario->sum('stock_actual')) }} unidades</span>
        </div>
    </div>

    {{-- Pie de página --}}
    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

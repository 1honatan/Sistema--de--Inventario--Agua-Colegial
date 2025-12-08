<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Producción</title>
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
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .info-section {
            background-color: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-row {
            display: inline-block;
            width: 48%;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #1e40af;
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

        .summary {
            background-color: #dbeafe;
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
            color: #1e40af;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 10px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>REPORTE DE PRODUCCIÓN</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- Información del Filtro --}}
    @if($validado['fecha_inicio'] ?? false || $validado['fecha_fin'] ?? false || $validado['id_producto'] ?? false)
    <div class="info-section">
        <h3 style="color: #1e40af; margin-bottom: 10px;">Filtros Aplicados</h3>

        @if($validado['fecha_inicio'] ?? false)
        <div class="info-row">
            <span class="info-label">Fecha Inicio:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_inicio'])->format('d/m/Y') }}
        </div>
        @endif

        @if($validado['fecha_fin'] ?? false)
        <div class="info-row">
            <span class="info-label">Fecha Fin:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_fin'])->format('d/m/Y') }}
        </div>
        @endif

        @if($validado['id_producto'] ?? false)
        <div class="info-row">
            <span class="info-label">Producto:</span>
            {{ $producciones->first()->producto->nombre ?? 'Todos' }}
        </div>
        @endif
    </div>
    @endif

    {{-- Tabla de Producciones --}}
    @forelse($producciones as $produccion)
    <div style="margin-bottom: 20px; page-break-inside: avoid;">
        <table style="margin-bottom: 10px;">
            <thead>
                <tr>
                    <th colspan="4" style="background-color: #4f46e5; text-align: left;">
                        Producción #{{ $produccion->id }} - {{ \Carbon\Carbon::parse($produccion->fecha)->format('d/m/Y') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 25%; font-weight: bold;">Responsable:</td>
                    <td style="width: 75%;" colspan="3">{{ $produccion->responsable ?? '-' }}</td>
                </tr>
                @if($produccion->observaciones)
                <tr>
                    <td style="font-weight: bold;">Observaciones:</td>
                    <td colspan="3">{{ $produccion->observaciones }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        @if($produccion->productos->count() > 0)
        <table style="margin-bottom: 5px;">
            <thead>
                <tr style="background-color: #818cf8;">
                    <th style="text-align: left;">Producto</th>
                    <th style="text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produccion->productos as $prod)
                    @if(empty($validado['id_producto']) || $prod->producto_id == $validado['id_producto'])
                    <tr>
                        <td>{{ $prod->producto->nombre ?? 'Producto' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($prod->cantidad) }} unidades</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif

        @if($produccion->materiales->count() > 0)
        <table>
            <thead>
                <tr style="background-color: #a78bfa;">
                    <th style="text-align: left;">Material</th>
                    <th style="text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produccion->materiales as $material)
                <tr>
                    <td>{{ $material->nombre_material }}</td>
                    <td style="text-align: right;">{{ number_format($material->cantidad, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @empty
        <table>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 40px; color: #666; font-size: 14px;">
                        <i style="font-style: normal; font-size: 48px; display: block; margin-bottom: 10px;">📋</i>
                        No hay registros de producción para los filtros seleccionados
                    </td>
                </tr>
            </tbody>
        </table>
    @endforelse

    {{-- Resumen --}}
    <div class="summary">
        <h3 style="color: #1e40af; margin-bottom: 15px;">Resumen Total</h3>

        <div class="summary-item">
            <span class="summary-label">Total de Registros:</span><br>
            <span class="summary-value">{{ $producciones->count() }}</span>
        </div>

        <div class="summary-item">
            <span class="summary-label">Cantidad Total Producida:</span><br>
            <span class="summary-value">{{ number_format($totalCantidad) }} unidades</span>
        </div>
    </div>

    {{-- Pie de página --}}
    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

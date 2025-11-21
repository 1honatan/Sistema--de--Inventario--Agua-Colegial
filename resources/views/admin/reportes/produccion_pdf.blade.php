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
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
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
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Lote</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @forelse($producciones as $produccion)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($produccion->fecha_produccion)->format('d/m/Y') }}</td>
                    <td>{{ $produccion->lote }}</td>
                    <td>{{ $produccion->producto->nombre }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($produccion->cantidad) }}</td>
                    <td>{{ $produccion->personal->nombre_completo ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de producción para los filtros seleccionados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

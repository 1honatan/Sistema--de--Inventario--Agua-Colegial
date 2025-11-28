<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Insumos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #9333ea; padding-bottom: 15px; }
        .header h1 { color: #9333ea; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #faf5ff; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #9333ea; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #9333ea; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #f3e8ff; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-label { font-weight: bold; color: #9333ea; }
        .summary-value { font-size: 16px; font-weight: bold; color: #7e22ce; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE INSUMOS</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #9333ea; margin-bottom: 10px;">Período del Reporte</h3>
        <div class="info-row">
            <span class="info-label">Fecha Inicio:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_inicio'])->format('d/m/Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Fecha Fin:</span>
            {{ \Carbon\Carbon::parse($validado['fecha_fin'])->format('d/m/Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto/Insumo</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Lote</th>
                <th>Vencimiento</th>
                <th>Proveedor</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @forelse($insumos as $insumo)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($insumo->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $insumo->producto_insumo ?? 'N/A' }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($insumo->cantidad, 2) }}</td>
                    <td>{{ $insumo->unidad_medida ?? 'N/A' }}</td>
                    <td>{{ $insumo->numero_lote ?? 'N/A' }}</td>
                    <td>{{ $insumo->fecha_vencimiento ? \Carbon\Carbon::parse($insumo->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $insumo->proveedor ?? 'N/A' }}</td>
                    <td>{{ $insumo->responsable ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de insumos para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #9333ea; margin-bottom: 15px;">Resumen Total</h3>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Total de Registros:</span><br>
            <span class="summary-value">{{ number_format($totalRegistros) }}</span>
        </div>
        @if($insumos->count() > 0)
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Insumos Diferentes:</span><br>
            <span class="summary-value">{{ $insumos->pluck('producto_insumo')->unique()->filter()->count() }}</span>
        </div>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Proveedores:</span><br>
            <span class="summary-value">{{ $insumos->pluck('proveedor')->unique()->filter()->count() }}</span>
        </div>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Cantidad Total:</span><br>
            <span class="summary-value">{{ number_format($insumos->sum('cantidad'), 2) }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

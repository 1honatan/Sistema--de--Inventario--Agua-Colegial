<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Tanques de Agua</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #0891b2; padding-bottom: 15px; }
        .header h1 { color: #0891b2; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #ecfeff; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #0891b2; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #0891b2; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #cffafe; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-label { font-weight: bold; color: #0891b2; }
        .summary-value { font-size: 16px; font-weight: bold; color: #0e7490; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE TANQUES DE AGUA</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #0891b2; margin-bottom: 10px;">Período del Reporte</h3>
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
                <th>Nombre Tanque</th>
                <th>Capacidad (L)</th>
                <th>Procedimiento</th>
                <th>Productos</th>
                <th>Responsable</th>
                <th>Próxima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tanques as $tanque)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($tanque->fecha_limpieza)->format('d/m/Y') }}</td>
                    <td>{{ $tanque->nombre_tanque ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ number_format($tanque->capacidad_litros, 0) }}</td>
                    <td>{{ Str::limit($tanque->procedimiento_limpieza ?? 'N/A', 20) }}</td>
                    <td>{{ Str::limit($tanque->productos_desinfeccion ?? 'N/A', 20) }}</td>
                    <td>{{ $tanque->responsable ?? 'N/A' }}</td>
                    <td>{{ $tanque->proxima_limpieza ? \Carbon\Carbon::parse($tanque->proxima_limpieza)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de tanques para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #0891b2; margin-bottom: 15px;">Resumen Total</h3>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Total de Limpiezas:</span><br>
            <span class="summary-value">{{ number_format($totalRegistros) }}</span>
        </div>
        @if($tanques->count() > 0)
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Tanques Diferentes:</span><br>
            <span class="summary-value">{{ $tanques->pluck('nombre_tanque')->unique()->filter()->count() }}</span>
        </div>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Capacidad Total (L):</span><br>
            <span class="summary-value">{{ number_format($tanques->sum('capacidad_litros'), 0) }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

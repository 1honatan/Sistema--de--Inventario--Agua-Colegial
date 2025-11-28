<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Mantenimiento de Equipos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #ea580c; padding-bottom: 15px; }
        .header h1 { color: #ea580c; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #fff7ed; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #ea580c; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #ea580c; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #ffedd5; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-label { font-weight: bold; color: #ea580c; }
        .summary-value { font-size: 16px; font-weight: bold; color: #c2410c; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE MANTENIMIENTO DE EQUIPOS</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #ea580c; margin-bottom: 10px;">Período del Reporte</h3>
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
                <th>Equipo</th>
                <th>Detalle</th>
                <th>Realizado Por</th>
                <th>Próxima Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mantenimientos as $mantenimiento)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mantenimiento->fecha)->format('d/m/Y') }}</td>
                    <td>{{ is_array($mantenimiento->equipo) ? implode(', ', $mantenimiento->equipo) : $mantenimiento->equipo }}</td>
                    <td>{{ Str::limit($mantenimiento->detalle_mantenimiento, 50) }}</td>
                    <td>{{ $mantenimiento->realizado_por }}</td>
                    <td>{{ $mantenimiento->proxima_fecha ? \Carbon\Carbon::parse($mantenimiento->proxima_fecha)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de mantenimiento para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #ea580c; margin-bottom: 15px;">Resumen Total</h3>
        <span class="summary-label">Total de Mantenimientos:</span><br>
        <span class="summary-value">{{ number_format($totalRegistros) }}</span>
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

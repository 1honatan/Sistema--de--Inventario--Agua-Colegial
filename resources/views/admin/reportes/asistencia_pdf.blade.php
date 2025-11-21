<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #ec4899; padding-bottom: 15px; }
        .header h1 { color: #ec4899; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #fdf2f8; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #ec4899; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #ec4899; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #fce7f3; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-label { font-weight: bold; color: #ec4899; }
        .summary-value { font-size: 16px; font-weight: bold; color: #db2777; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ASISTENCIA</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #ec4899; margin-bottom: 10px;">Período del Reporte</h3>
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
                <th>Personal</th>
                <th>Día</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asistencias as $asistencia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $asistencia->personal->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $asistencia->dia_semana }}</td>
                    <td style="text-align: center;">{{ $asistencia->entrada_hora ? \Carbon\Carbon::parse($asistencia->entrada_hora)->format('H:i') : '-' }}</td>
                    <td style="text-align: center;">{{ $asistencia->salida_hora ? \Carbon\Carbon::parse($asistencia->salida_hora)->format('H:i') : '-' }}</td>
                    <td style="text-align: center;">
                        @if($asistencia->estado == 'presente')
                            <span style="color: #059669;">Presente</span>
                        @elseif($asistencia->estado == 'ausente')
                            <span style="color: #dc2626;">Ausente</span>
                        @elseif($asistencia->estado == 'permiso')
                            <span style="color: #d97706;">Permiso</span>
                        @else
                            <span style="color: #f59e0b;">Tardanza</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de asistencia para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #ec4899; margin-bottom: 15px;">Resumen Total</h3>
        <span class="summary-label">Total de Registros:</span><br>
        <span class="summary-value">{{ number_format($totalRegistros) }}</span>
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

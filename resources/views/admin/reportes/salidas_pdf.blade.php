<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Salidas de Productos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #0284c7; padding-bottom: 15px; }
        .header h1 { color: #0284c7; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #f0f9ff; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #0284c7; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #0284c7; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #dbeafe; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-item { display: inline-block; width: 48%; margin-bottom: 5px; }
        .summary-label { font-weight: bold; color: #0284c7; }
        .summary-value { font-size: 16px; font-weight: bold; color: #0369a1; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE SALIDAS DE PRODUCTOS</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #0284c7; margin-bottom: 10px;">Período del Reporte</h3>
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
                <th>Chofer</th>
                <th>Distribuidor</th>
                <th>Vehículo</th>
                <th>Retornos</th>
                <th>Hora Llegada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salidas as $salida)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $salida->chofer ?? '-' }}</td>
                    <td>{{ $salida->nombre_distribuidor }}</td>
                    <td>{{ $salida->vehiculo_placa ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($salida->retornos) }}</td>
                    <td>{{ $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : 'Sin registrar' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de salidas para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #0284c7; margin-bottom: 15px;">Resumen Total</h3>
        <div class="summary-item">
            <span class="summary-label">Total de Salidas:</span><br>
            <span class="summary-value">{{ number_format($totalRegistros) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Fosa Séptica</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #0d9488; padding-bottom: 15px; }
        .header h1 { color: #0d9488; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 10px; }
        .info-section { background-color: #f0fdfa; padding: 12px; margin-bottom: 20px; border-radius: 5px; }
        .info-row { display: inline-block; width: 48%; margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #0d9488; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background-color: #0d9488; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .summary { background-color: #ccfbf1; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .summary-label { font-weight: bold; color: #0d9488; }
        .summary-value { font-size: 16px; font-weight: bold; color: #0f766e; }
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 2px solid #e5e7eb; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE FOSA SÉPTICA</h1>
        <p>Sistema de Gestión - Agua Colegial</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <h3 style="color: #0d9488; margin-bottom: 10px;">Período del Reporte</h3>
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
                <th>Tipo de Fosa</th>
                <th>Detalle del Trabajo</th>
                <th>Responsable</th>
                <th>Empresa</th>
                <th>Próxima Limpieza</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fosas as $fosa)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($fosa->fecha_limpieza)->format('d/m/Y') }}</td>
                    <td>{{ $fosa->tipo_fosa ?? 'N/A' }}</td>
                    <td>{{ Str::limit($fosa->detalle_trabajo ?? 'Sin detalle', 30) }}</td>
                    <td>{{ $fosa->responsable ?? 'N/A' }}</td>
                    <td>{{ $fosa->empresa_contratada ?? 'N/A' }}</td>
                    <td>{{ $fosa->proxima_limpieza ? \Carbon\Carbon::parse($fosa->proxima_limpieza)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        No hay registros de fosa séptica para el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3 style="color: #0d9488; margin-bottom: 15px;">Resumen Total</h3>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Total de Limpiezas:</span><br>
            <span class="summary-value">{{ number_format($totalRegistros) }}</span>
        </div>
        @if($fosas->count() > 0)
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Tipos de Fosa:</span><br>
            <span class="summary-value">{{ $fosas->pluck('tipo_fosa')->unique()->filter()->count() }}</span>
        </div>
        <div style="display: inline-block; width: 48%; margin-bottom: 10px;">
            <span class="summary-label">Empresas Contratadas:</span><br>
            <span class="summary-value">{{ $fosas->pluck('empresa_contratada')->unique()->filter()->count() }}</span>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestión Integral - Agua Colegial</p>
        <p>Documento generado automáticamente - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>

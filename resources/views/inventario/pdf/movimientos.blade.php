<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos - Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #1e3a8a;
        }

        .header h1 {
            color: #1e3a8a;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 10px;
        }

        .info-section {
            margin-bottom: 15px;
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 4px;
        }

        .info-section h3 {
            color: #1e3a8a;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-item {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 3px 10px 3px 0;
            width: 30%;
        }

        .info-value {
            display: table-cell;
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #1e3a8a;
            color: white;
        }

        thead th {
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }

        tbody td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:hover {
            background-color: #f3f4f6;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-entrada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-salida {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .text-green {
            color: #059669;
            font-weight: bold;
        }

        .text-red {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }

        .summary {
            margin-top: 15px;
            background-color: #eff6ff;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #1e3a8a;
        }

        .summary h3 {
            color: #1e3a8a;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 5px;
        }

        .summary-label {
            font-size: 8px;
            color: #6b7280;
            display: block;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>Historial de Movimientos de Inventario</h1>
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Filtros Aplicados --}}
    @if($fechaInicio || $fechaFin || $tipoMovimiento || $idProducto || $idUsuario)
        <div class="info-section">
            <h3>Filtros Aplicados</h3>
            <div class="info-grid">
                @if($fechaInicio)
                    <div class="info-item">
                        <span class="info-label">Fecha Inicio:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</span>
                    </div>
                @endif
                @if($fechaFin)
                    <div class="info-item">
                        <span class="info-label">Fecha Fin:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
                    </div>
                @endif
                @if($tipoMovimiento)
                    <div class="info-item">
                        <span class="info-label">Tipo:</span>
                        <span class="info-value">{{ ucfirst($tipoMovimiento) }}</span>
                    </div>
                @endif
                @if($productoNombre)
                    <div class="info-item">
                        <span class="info-label">Producto:</span>
                        <span class="info-value">{{ $productoNombre }}</span>
                    </div>
                @endif
                @if($usuarioNombre)
                    <div class="info-item">
                        <span class="info-label">Usuario:</span>
                        <span class="info-value">{{ $usuarioNombre }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Resumen --}}
    <div class="summary">
        <h3>Resumen General</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total Movimientos</span>
                <span class="summary-value">{{ $movimientos->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Entradas</span>
                <span class="summary-value" style="color: #059669;">{{ $movimientos->where('tipo_movimiento', 'entrada')->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Salidas</span>
                <span class="summary-value" style="color: #dc2626;">{{ $movimientos->where('tipo_movimiento', 'salida')->count() }}</span>
            </div>
        </div>
    </div>

    {{-- Tabla de Movimientos --}}
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 18%;">Producto</th>
                <th style="width: 8%;">Tipo</th>
                <th style="width: 8%;">Cantidad</th>
                <th style="width: 12%;">Origen</th>
                <th style="width: 12%;">Destino</th>
                <th style="width: 12%;">Usuario</th>
                <th style="width: 22%;">Observación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $movimiento)
                <tr>
                    <td>
                        {{ $movimiento->fecha_movimiento->format('d/m/Y') }}<br>
                        <span style="color: #9ca3af; font-size: 7px;">{{ $movimiento->fecha_movimiento->format('H:i') }}</span>
                    </td>
                    <td>
                        <strong>{{ $movimiento->producto->nombre }}</strong><br>
                        @if($movimiento->producto->tipoProducto)
                            <span style="color: #6b7280; font-size: 7px;">{{ $movimiento->producto->tipoProducto->nombre }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $movimiento->tipo_movimiento }}">
                            {{ ucfirst($movimiento->tipo_movimiento) }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $movimiento->tipo_movimiento === 'entrada' ? 'text-green' : 'text-red' }}">
                            {{ $movimiento->tipo_movimiento === 'entrada' ? '+' : '-' }}{{ number_format($movimiento->cantidad) }}
                        </span>
                    </td>
                    <td>{{ $movimiento->origen ?? '-' }}</td>
                    <td>{{ $movimiento->destino ?? '-' }}</td>
                    <td>
                        {{ $movimiento->usuario->nombre ?? '-' }}<br>
                        @if($movimiento->usuario && $movimiento->usuario->personal)
                            <span style="color: #6b7280; font-size: 7px;">{{ $movimiento->usuario->personal->nombre_completo }}</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($movimiento->observacion ?? '-', 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #9ca3af;">
                        No hay movimientos registrados con los filtros seleccionados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Gestión de Inventario</p>
        <p>Página {PAGENO} de {nbpg}</p>
    </div>
</body>
</html>

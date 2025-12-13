@extends('layouts.app')

@section('title', 'Inicio')

@section('page-title', 'Sistema de Inventario Agua Colegial')
@section('page-subtitle', 'Gestión integral de producción, inventario, ventas y control de personal en tiempo real')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
        min-height: 100vh;
        position: relative;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    /* Contenedor de Tarjetas - Fila Horizontal */
    .cards-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.5rem;
        justify-content: center;
        overflow-x: auto;
        padding: 0.5rem;
    }

    /* Tarjetas Cuadradas */
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        width: 110px;
        height: 110px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card-header {
        padding: 0.5rem;
        color: white;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .stat-card-icon {
        font-size: 18px;
        margin-bottom: 4px;
    }

    .stat-card-title {
        font-size: 0.65rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
    }

    .stat-card-body {
        padding: 0.4rem;
        background: #f8fafc;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-number {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.55rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Colores de Headers */
    .bg-blue { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .bg-indigo { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); }
    .bg-orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .bg-green { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
    .bg-teal { background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); }
    .bg-cyan { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .bg-purple { background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%); }
    .bg-pink { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }

    /* Secciones de Información */
    .info-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .section-header i {
        font-size: 1.5rem;
        color: #0ea5e9;
    }

    .section-header h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    /* Items de Movimiento */
    .movement-item {
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .movement-item:hover {
        background: #f8fafc;
        border-left-color: #0ea5e9;
    }

    .movement-item:last-child {
        margin-bottom: 0;
    }

    /* Badges */
    .badge-status {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
    }

    .badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-success {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Botones */
    .btn-view {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        color: white;
    }

    /* Alertas de Stock */
    .alert-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 1rem;
    }

    .alert-card-title {
        font-weight: 700;
        color: #92400e;
        font-size: 0.95rem;
    }

    .alert-card-stock {
        font-size: 1.5rem;
        font-weight: 800;
        color: #b45309;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Título Principal del Sistema -->
    <div class="mb-5" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%); padding: 3rem 2rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(14, 165, 233, 0.3); position: relative; overflow: hidden;">
            <!-- Efecto de fondo animado -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23ffffff\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); opacity: 0.5;"></div>

            <!-- Contenido del título -->
            <div style="position: relative; z-index: 1;">
                <div style="margin-bottom: 1rem;">
                    <i class="fas fa-water" style="font-size: 4rem; color: white; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></i>
                </div>
                <h1 style="font-size: 3rem; font-weight: 900; color: white; text-transform: uppercase; letter-spacing: 2px; margin: 0; text-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1.2;">
                    SISTEMA DE INVENTARIO
                    <span style="display: block; font-size: 3.5rem; margin-top: 0.5rem; color: #fff; text-shadow: 0 6px 16px rgba(0,0,0,0.4);">
                        "AGUA COLEGIAL"
                    </span>
                </h1>
                <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.95); margin-top: 1.5rem; font-weight: 500; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                    Plataforma Integral de Registro y Control Operativo en Tiempo Real
                </p>
            </div>
        </div>
    </div>

    <!-- Título de Acceso Rápido -->
    <div class="mb-3" style="text-align: center;">
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0c4a6e; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-bolt" style="color: #0ea5e9; font-size: 1.5rem;"></i>
            Acceso Rápido
            <i class="fas fa-bolt" style="color: #0ea5e9; font-size: 1.5rem;"></i>
        </h2>
        <div style="height: 3px; background: linear-gradient(to right, transparent, #0ea5e9, transparent); margin-top: 0.5rem; border-radius: 2px;"></div>
    </div>

    <!-- Tarjetas en Fila Horizontal -->
    <div class="cards-row mb-4">
        <a href="{{ route('control.salidas.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-blue">
                    <div class="stat-card-icon"><i class="fas fa-truck-loading"></i></div>
                    <h6 class="stat-card-title">Salidas</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-salidas">{{ $totalSalidas }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.produccion.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-indigo">
                    <div class="stat-card-icon"><i class="fas fa-industry"></i></div>
                    <h6 class="stat-card-title">Producción</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-produccion">{{ $totalProduccionDiaria }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.mantenimiento.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-orange">
                    <div class="stat-card-icon"><i class="fas fa-tools"></i></div>
                    <h6 class="stat-card-title">Mantenimiento</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-mantenimientos">{{ $totalMantenimientos }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.fumigacion.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-green">
                    <div class="stat-card-icon"><i class="fas fa-spray-can"></i></div>
                    <h6 class="stat-card-title">Fumigación</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-fumigaciones">{{ $totalFumigaciones }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.fosa-septica.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-teal">
                    <div class="stat-card-icon"><i class="fas fa-toilet"></i></div>
                    <h6 class="stat-card-title">Fosa Séptica</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-fosa">{{ $totalFosaSeptica }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.tanques.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-cyan">
                    <div class="stat-card-icon"><i class="fas fa-water"></i></div>
                    <h6 class="stat-card-title">Tanques</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-tanques">{{ $totalTanques }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.insumos.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-purple">
                    <div class="stat-card-icon"><i class="fas fa-box-open"></i></div>
                    <h6 class="stat-card-title">Insumos</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-insumos">{{ $totalInsumos }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>

        <a href="{{ route('control.asistencia-semanal.index') }}" style="text-decoration: none;">
            <div class="stat-card">
                <div class="stat-card-header bg-pink">
                    <div class="stat-card-icon"><i class="fas fa-user-check"></i></div>
                    <h6 class="stat-card-title">Asistencia</h6>
                </div>
                <div class="stat-card-body">
                    <div class="stat-number" id="total-asistencias">{{ $totalAsistencias }}</div>
                    <div class="stat-label">Registros</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Secciones Informativas - Primera Fila -->
    <div class="row g-3 mb-4">
        <!-- Listado de Personal -->
        <div class="col-lg-4">
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-users"></i>
                    <h4>Personal</h4>
                </div>
                @if($listaPersonal->count() > 0)
                    @foreach($listaPersonal as $persona)
                    <div class="movement-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.85rem;">{{ $persona->nombre_completo }}</div>
                                <small class="text-muted">{{ $persona->cargo }}</small>
                            </div>
                            <div>
                                <span class="badge-status badge-success">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Activo
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="btn-view">
                            Ver Todos <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <p>No hay personal registrado</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Listado de Inventario -->
        <div class="col-lg-4">
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-warehouse"></i>
                    <h4>Inventario</h4>
                </div>
                @if($listaInventario->count() > 0)
                    @foreach($listaInventario as $movimiento)
                    <div class="movement-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.85rem;">{{ $movimiento->producto->nombre ?? 'N/A' }}</div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m H:i') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <div style="font-weight: 800; color: {{ $movimiento->tipo_movimiento == 'entrada' ? '#16a34a' : '#dc2626' }}; font-size: 0.9rem;">
                                    {{ $movimiento->tipo_movimiento == 'entrada' ? '+' : '-' }}{{ number_format($movimiento->cantidad) }}
                                </div>
                                <small class="text-muted">{{ $movimiento->tipo_movimiento }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('inventario.movimiento.historial') }}" class="btn-view">
                            Ver Todos <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-exchange-alt"></i>
                        <p>No hay movimientos registrados</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Secciones Informativas - Segunda Fila -->
    <div class="row g-3 mb-4">
        <!-- Últimas Salidas -->
        <div class="col-12">
            <div class="info-section">
                <div class="section-header">
                    <i class="fas fa-truck-loading"></i>
                    <h4>Últimas Salidas</h4>
                </div>
                <div id="ultimas-salidas-container">
                @if($ultimasSalidas->count() > 0)
                    @foreach($ultimasSalidas as $salida)
                    <div class="movement-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;">{{ $salida->nombre_distribuidor }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}
                                    @if($salida->vehiculo_placa)
                                        - <i class="fas fa-truck"></i> {{ $salida->vehiculo_placa }}
                                    @endif
                                </small>
                            </div>
                            <div class="text-end">
                                <div style="font-weight: 800; color: #0284c7; font-size: 1.2rem;">{{ $salida->botellones }}</div>
                                <small class="text-muted">botellones</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('control.salidas.index') }}" class="btn-view">
                            Ver Todas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No hay salidas registradas</p>
                    </div>
                @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar cada 10 segundos
    const REFRESH_INTERVAL = 10000;

    function actualizarDashboard() {
        fetch('{{ route("admin.dashboard.data") }}')
            .then(response => response.json())
            .then(data => {
                // Actualizar totales en tarjetas
                document.getElementById('total-salidas').textContent = data.totales.salidas;
                document.getElementById('total-produccion').textContent = data.totales.produccion;
                document.getElementById('total-mantenimientos').textContent = data.totales.mantenimientos;
                document.getElementById('total-fumigaciones').textContent = data.totales.fumigaciones;
                document.getElementById('total-fosa').textContent = data.totales.fosa_septica;
                document.getElementById('total-tanques').textContent = data.totales.tanques;
                document.getElementById('total-insumos').textContent = data.totales.insumos;
                document.getElementById('total-asistencias').textContent = data.totales.asistencias;

                // Actualizar últimas salidas
                actualizarUltimasSalidas(data.ultimas_salidas);

                // Actualizar mantenimientos pendientes
                actualizarMantenimientos(data.mantenimientos_pendientes);
            })
            .catch(error => console.error('Error actualizando dashboard:', error));
    }

    function actualizarUltimasSalidas(salidas) {
        const container = document.getElementById('ultimas-salidas-container');
        if (salidas.length > 0) {
            let html = '';
            salidas.forEach(salida => {
                html += `
                    <div class="movement-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;">${salida.nombre_distribuidor}</div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> ${salida.fecha}
                                    ${salida.vehiculo_placa ? `- <i class="fas fa-truck"></i> ${salida.vehiculo_placa}` : ''}
                                </small>
                            </div>
                            <div class="text-end">
                                <div style="font-weight: 800; color: #0284c7; font-size: 1.2rem;">${salida.botellones}</div>
                                <small class="text-muted">botellones</small>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += `
                <div class="text-center mt-3">
                    <a href="{{ route('control.salidas.index') }}" class="btn-view">
                        Ver Todas <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            `;
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No hay salidas registradas</p>
                </div>
            `;
        }
    }

    function actualizarMantenimientos(mantenimientos) {
        const container = document.getElementById('mantenimientos-container');
        if (mantenimientos.length > 0) {
            let html = '';
            mantenimientos.forEach(mant => {
                html += `
                    <div class="movement-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;">${mant.equipo}</div>
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> ${mant.realizado_por}
                                    ${mant.proxima_fecha ? `- <i class="fas fa-calendar"></i> ${mant.proxima_fecha}` : ''}
                                </small>
                            </div>
                            <div>
                                <span class="badge-status ${mant.estado_clase}">${mant.estado}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += `
                <div class="text-center mt-3">
                    <a href="{{ route('control.mantenimiento.index') }}" class="btn-view">
                        Ver Todos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            `;
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>No hay mantenimientos pendientes</p>
                </div>
            `;
        }
    }

    // Iniciar actualización periódica
    setInterval(actualizarDashboard, REFRESH_INTERVAL);
});
</script>
@endpush

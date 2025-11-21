@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

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

    .vehiculo-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .vehiculo-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .vehiculo-header {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vehiculo-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: #1f2937;
        font-weight: 500;
    }

    .badge-placa {
        display: inline-block;
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 1px;
    }

    .badge-estado {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-estado-activo {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .badge-estado-mantenimiento {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .badge-estado-inactivo {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .capacidad-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #e0f2fe;
        color: #075985;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Tarjetas de Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon-blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .stat-icon-green {
        background: #d1fae5;
        color: #059669;
    }

    .stat-icon-yellow {
        background: #fef3c7;
        color: #d97706;
    }

    .stat-icon-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-car mr-2"></i>
                        Gestión de Vehículos
                    </h3>
                    <p class="modern-card-subtitle">
                        Administración de la flota de distribución
                    </p>
                </div>
                <div class="modern-card-body">
                    <!-- Estadísticas -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Total Vehículos</div>
                                <div class="stat-value">{{ $totalVehiculos ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Activos</div>
                                <div class="stat-value">{{ $activos ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon stat-icon-yellow">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">En Mantenimiento</div>
                                <div class="stat-value">{{ $enMantenimiento ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon stat-icon-red">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">Inactivos</div>
                                <div class="stat-value">{{ $inactivos ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('admin.vehiculos.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nuevo Vehículo
                        </a>
                    </div>

                    @if(isset($vehiculos) && count($vehiculos) > 0)
                        @foreach($vehiculos as $v)
                        <div class="vehiculo-card">
                            <div class="vehiculo-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-car-side mr-2"></i>
                                        <span class="badge-placa">{{ $v->placa }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.vehiculos.edit', $v) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.vehiculos.toggle-estado', $v) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info" title="Cambiar estado">
                                            <i class="fas fa-sync-alt"></i> Estado
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.vehiculos.destroy', $v) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de eliminar este vehículo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="vehiculo-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-industry text-primary"></i> Marca
                                        </span>
                                        <span class="info-value">{{ $v->marca ?? 'N/A' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-car text-info"></i> Modelo
                                        </span>
                                        <span class="info-value">{{ $v->modelo ?? 'N/A' }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-boxes text-success"></i> Capacidad
                                        </span>
                                        <span class="capacidad-badge">
                                            <i class="fas fa-cubes"></i>
                                            {{ $v->capacidad ? number_format($v->capacidad) . ' unidades' : 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-traffic-light text-warning"></i> Estado
                                        </span>
                                        @if($v->estado == 'activo')
                                            <span class="badge-estado badge-estado-activo">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        @elseif($v->estado == 'mantenimiento')
                                            <span class="badge-estado badge-estado-mantenimiento">
                                                <i class="fas fa-wrench"></i> Mantenimiento
                                            </span>
                                        @else
                                            <span class="badge-estado badge-estado-inactivo">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($v->año || $v->color || $v->observaciones)
                                <div class="info-grid mt-3">
                                    @if($v->año)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar"></i> Año
                                        </span>
                                        <span class="info-value">{{ $v->año }}</span>
                                    </div>
                                    @endif

                                    @if($v->color)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-palette"></i> Color
                                        </span>
                                        <span class="info-value">{{ $v->color }}</span>
                                    </div>
                                    @endif

                                    @if($v->observaciones)
                                    <div class="info-item" style="grid-column: 1 / -1;">
                                        <span class="info-label">
                                            <i class="fas fa-sticky-note"></i> Observaciones
                                        </span>
                                        <span class="info-value">{{ $v->observaciones }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-car text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay vehículos registrados.</p>
                            <a href="{{ route('admin.vehiculos.create') }}" class="btn-modern btn-primary mt-3">
                                <i class="fas fa-plus-circle"></i>
                                Registrar Primer Vehículo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

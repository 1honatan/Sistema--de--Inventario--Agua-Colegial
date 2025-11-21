@extends('layouts.app')

@section('title', 'Control de Mantenimiento')

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

    .mantenimiento-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .mantenimiento-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .mantenimiento-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mantenimiento-body {
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

    .badge-equipo {
        display: inline-block;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #e0f2fe;
        color: #075985;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .equipos-list {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #f97316;
    }

    .equipo-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: white;
        margin-bottom: 0.5rem;
        border-radius: 6px;
        font-weight: 600;
        color: #1f2937;
    }

    .equipo-item:last-child {
        margin-bottom: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .status-badge-vencido {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .status-badge-proximo {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .status-badge-vigente {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-badge-sin-programar {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        color: #374151;
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
                        <i class="fas fa-tools mr-2"></i>
                        Control de Mantenimiento de Equipos
                    </h3>
                    <p class="modern-card-subtitle">
                        Registro y seguimiento de mantenimientos de equipos
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.mantenimiento.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nuevo Mantenimiento
                        </a>
                    </div>

                    @if($mantenimientos->count() > 0)
                        @foreach($mantenimientos as $m)
                        @php
                            $hoy = \Carbon\Carbon::now();
                            $proximaFecha = $m->proxima_fecha ? \Carbon\Carbon::parse($m->proxima_fecha) : null;

                            if (!$proximaFecha) {
                                $estadoClass = 'sin-programar';
                                $estadoLabel = 'Sin programar';
                                $estadoIcon = 'fa-calendar-times';
                            } elseif ($proximaFecha->isPast()) {
                                $estadoClass = 'vencido';
                                $estadoLabel = 'Vencido';
                                $estadoIcon = 'fa-exclamation-triangle';
                            } elseif ($proximaFecha->diffInDays($hoy) <= 7) {
                                $estadoClass = 'proximo';
                                $estadoLabel = 'Próximo';
                                $estadoIcon = 'fa-clock';
                            } else {
                                $estadoClass = 'vigente';
                                $estadoLabel = 'Vigente';
                                $estadoIcon = 'fa-check-circle';
                            }
                        @endphp
                        <div class="mantenimiento-card">
                            <div class="mantenimiento-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs mr-2"></i>
                                        <span class="badge-equipo">
                                            @if(is_array($m->equipo))
                                                Mantenimiento #{{ $m->id }}
                                            @else
                                                {{ $m->equipo }}
                                            @endif
                                        </span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.mantenimiento.show', $m) }}"
                                       class="btn btn-sm btn-light"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('control.mantenimiento.edit', $m) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.mantenimiento.destroy', $m) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de eliminar este registro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mantenimiento-body">
                                <!-- Equipos (si es array) -->
                                @if(is_array($m->equipo))
                                <div class="mb-3">
                                    <span class="info-label mb-2 d-block">
                                        <i class="fas fa-cogs text-orange-600"></i> Equipos Mantenidos
                                    </span>
                                    <div class="equipos-list">
                                        @foreach($m->equipo as $equipo)
                                        <div class="equipo-item">
                                            <i class="fas fa-circle text-orange-500" style="font-size: 0.5rem;"></i>
                                            {{ $equipo }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Información General -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Mantenimiento
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $m->fecha->format('d/m/Y') }}
                                            <small>({{ $m->fecha->locale('es')->isoFormat('dddd') }})</small>
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-check text-success"></i> Realizado por
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $m->personal ? $m->personal->nombre_completo : $m->realizado_por }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-tie text-purple-600"></i> Supervisado por
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-user-shield mr-1"></i>
                                            {{ $m->supervisado_por }}
                                        </span>
                                    </div>

                                    @if($proximaFecha)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-plus text-warning"></i> Próximo Mantenimiento
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-day"></i>
                                            {{ $proximaFecha->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-info-circle"></i> Estado
                                        </span>
                                        <span class="status-badge status-badge-{{ $estadoClass }}">
                                            <i class="fas {{ $estadoIcon }}"></i>
                                            {{ $estadoLabel }}
                                        </span>
                                    </div>
                                </div>

                                @if($m->observaciones)
                                <div class="info-item mt-3" style="grid-column: 1 / -1;">
                                    <span class="info-label">
                                        <i class="fas fa-sticky-note"></i> Observaciones
                                    </span>
                                    <span class="info-value">{{ $m->observaciones }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $mantenimientos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tools text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de mantenimiento de equipos.</p>
                            <a href="{{ route('control.mantenimiento.create') }}" class="btn-modern btn-primary mt-3">
                                <i class="fas fa-plus-circle"></i>
                                Crear Primer Registro
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

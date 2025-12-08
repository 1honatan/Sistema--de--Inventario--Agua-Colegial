@extends('layouts.app')

@section('title', 'Detalle de Mantenimiento')

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

    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .detail-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .detail-body {
        padding: 2rem;
    }

    .info-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #f97316;
    }

    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        color: #1f2937;
        font-weight: 500;
    }

    .equipos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 0.75rem;
    }

    .equipo-badge {
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border-left: 3px solid #f97316;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #1f2937;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
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

    .date-display {
        background: #e0f2fe;
        color: #075985;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .observaciones-box {
        background: #fffbeb;
        border: 2px solid #fbbf24;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-tools mr-2"></i>
                                Detalle de Mantenimiento #{{ $mantenimiento->id }}
                            </h3>
                            <p class="mb-0 opacity-90">
                                Información completa del registro de mantenimiento
                            </p>
                        </div>
                        <div>
                            @php
                                $hoy = \Carbon\Carbon::now();
                                $proximaFecha = $mantenimiento->proxima_fecha ? \Carbon\Carbon::parse($mantenimiento->proxima_fecha) : null;

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
                            <span class="status-badge status-badge-{{ $estadoClass }}">
                                <i class="fas {{ $estadoIcon }}"></i>
                                {{ $estadoLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Equipos Mantenidos -->
                    <div class="info-section">
                        <h5 class="mb-3">
                            <i class="fas fa-cogs text-orange-600 mr-2"></i>
                            Equipos Mantenidos
                        </h5>
                        @if(is_array($mantenimiento->equipo))
                            <div class="equipos-grid">
                                @foreach($mantenimiento->equipo as $equipo)
                                <div class="equipo-badge">
                                    <i class="fas fa-wrench text-orange-500"></i>
                                    {{ $equipo }}
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="equipo-badge">
                                <i class="fas fa-wrench text-orange-500"></i>
                                {{ $mantenimiento->equipo }}
                            </div>
                        @endif
                    </div>

                    <!-- Información General -->
                    <div class="info-section">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Información General
                        </h5>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt text-info"></i>
                                Fecha de Mantenimiento
                            </div>
                            <div class="info-value">
                                <span class="date-display">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $mantenimiento->fecha->format('d/m/Y') }}
                                    <small>({{ $mantenimiento->fecha->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }})</small>
                                </span>
                            </div>
                        </div>

                        @if($proximaFecha)
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus text-warning"></i>
                                Próximo Mantenimiento
                            </div>
                            <div class="info-value">
                                <span class="date-display">
                                    <i class="fas fa-calendar-day"></i>
                                    {{ $proximaFecha->format('d/m/Y') }}
                                    <small>({{ $proximaFecha->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }})</small>
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-user-check text-success"></i>
                                Realizado por
                            </div>
                            <div class="info-value">
                                <i class="fas fa-user mr-2"></i>
                                {{ $mantenimiento->personal ? $mantenimiento->personal->nombre_completo : $mantenimiento->realizado_por }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-user-tie text-purple-600"></i>
                                Supervisado por
                            </div>
                            <div class="info-value">
                                <i class="fas fa-user-shield mr-2"></i>
                                {{ $mantenimiento->supervisado_por }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-clock text-secondary"></i>
                                Fecha de Registro
                            </div>
                            <div class="info-value">
                                {{ $mantenimiento->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        @if($mantenimiento->updated_at->ne($mantenimiento->created_at))
                        <div class="info-row">
                            <div class="info-label">
                                <i class="fas fa-edit text-secondary"></i>
                                Última Actualización
                            </div>
                            <div class="info-value">
                                {{ $mantenimiento->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Observaciones -->
                    @if($mantenimiento->observaciones)
                    <div class="observaciones-box">
                        <h5 class="mb-3">
                            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                            Observaciones
                        </h5>
                        <p class="mb-0" style="color: #78350f; line-height: 1.6;">
                            {{ $mantenimiento->observaciones }}
                        </p>
                    </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="action-buttons">
                        <a href="{{ route('control.mantenimiento.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Volver al Listado
                        </a>
                        <a href="{{ route('control.mantenimiento.edit', $mantenimiento) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i>
                            Editar Mantenimiento
                        </a>
                        <form action="{{ route('control.mantenimiento.destroy', $mantenimiento) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('¿Está seguro de eliminar este registro de mantenimiento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

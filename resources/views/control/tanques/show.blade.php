@extends('layouts.app')

@section('title', 'Detalle de Limpieza de Tanque')

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
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .detail-header {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        color: white;
        padding: 1.5rem;
    }

    .detail-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #0891b2;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
    }

    .info-value-large {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0891b2;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .text-block {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        white-space: pre-wrap;
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-water mr-2"></i>
                                {{ $tanque->nombre_tanque }}
                            </h3>
                            <small>Detalle de Limpieza de Tanque</small>
                        </div>
                        <div>
                            <a href="{{ route('control.tanques.edit', $tanque) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('control.tanques.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Información Principal -->
                    <div class="info-grid mb-4">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i> Fecha de Limpieza
                            </div>
                            <div class="info-value-large">
                                {{ $tanque->fecha_limpieza->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">{{ $tanque->fecha_limpieza->locale('es')->isoFormat('dddd') }}</small>
                        </div>

                        @if($tanque->capacidad_litros)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-fill-drip"></i> Capacidad
                            </div>
                            <div class="info-value-large">
                                {{ number_format($tanque->capacidad_litros) }} L
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-check"></i> Responsable
                            </div>
                            <div class="info-value">{{ $tanque->responsable }}</div>
                        </div>

                        @if($tanque->supervisado_por)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user-shield"></i> Supervisado por
                            </div>
                            <div class="info-value">{{ $tanque->supervisado_por }}</div>
                        </div>
                        @endif

                        @if($tanque->proxima_limpieza)
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus"></i> Próxima Limpieza
                            </div>
                            <div class="info-value-large">
                                {{ $tanque->proxima_limpieza->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Procedimiento de Limpieza -->
                    @if($tanque->procedimiento_limpieza)
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-list-ol text-primary"></i> Procedimiento de Limpieza
                        </h5>
                        <div class="text-block">{{ $tanque->procedimiento_limpieza }}</div>
                    </div>
                    @endif

                    <!-- Productos de Desinfección -->
                    @if($tanque->productos_desinfeccion)
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-flask text-success"></i> Productos de Desinfección
                        </h5>
                        <div class="text-block">{{ $tanque->productos_desinfeccion }}</div>
                    </div>
                    @endif

                    <!-- Observaciones -->
                    @if($tanque->observaciones)
                    <div class="mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-sticky-note text-warning"></i> Observaciones
                        </h5>
                        <div class="text-block">{{ $tanque->observaciones }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

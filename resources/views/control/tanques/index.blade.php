@extends('layouts.app')

@section('title', 'Control Tanques de Agua')

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

    .tanque-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .tanque-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .tanque-header {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tanque-body {
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

    .badge-tanque {
        display: inline-block;
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
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

    .date-badge.proxima {
        background: #cffafe;
        color: #155e75;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .capacidad-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #cffafe;
        color: #164e63;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
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
                        <i class="fas fa-water mr-2"></i>
                        Control de Limpiezas de Tanques de Agua
                    </h3>
                    <p class="modern-card-subtitle">
                        Registro y seguimiento de limpiezas de tanques
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.tanques.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nueva Limpieza
                        </a>
                    </div>

                    @if($tanques->count() > 0)
                        @foreach($tanques as $t)
                        <div class="tanque-card">
                            <div class="tanque-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-tint mr-2"></i>
                                        <span class="badge-tanque">{{ $t->nombre_tanque }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.tanques.show', $t) }}"
                                       class="btn btn-sm btn-light"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('control.tanques.edit', $t) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.tanques.destroy', $t) }}"
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
                            <div class="tanque-body">
                                <!-- Información General -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Limpieza
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $t->fecha_limpieza->format('d/m/Y') }}
                                            <small>({{ $t->fecha_limpieza->locale('es')->isoFormat('dddd') }})</small>
                                        </span>
                                    </div>

                                    @if($t->capacidad_litros)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-fill-drip text-cyan-600"></i> Capacidad
                                        </span>
                                        <span class="capacidad-badge">
                                            <i class="fas fa-water"></i>
                                            {{ number_format($t->capacidad_litros) }} Litros
                                        </span>
                                    </div>
                                    @endif

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-check text-primary"></i> Responsable
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $t->responsable }}
                                        </span>
                                    </div>

                                    @if($t->proxima_limpieza)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-plus text-warning"></i> Próxima Limpieza
                                        </span>
                                        <span class="date-badge proxima">
                                            <i class="fas fa-calendar-day"></i>
                                            {{ $t->proxima_limpieza->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                @if($t->observaciones)
                                <div class="info-item mt-3" style="grid-column: 1 / -1;">
                                    <span class="info-label">
                                        <i class="fas fa-sticky-note"></i> Observaciones
                                    </span>
                                    <span class="info-value">{{ $t->observaciones }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $tanques->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-water text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de limpiezas de tanques.</p>
                            <a href="{{ route('control.tanques.create') }}" class="btn-modern btn-primary mt-3">
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

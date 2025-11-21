@extends('layouts.app')

@section('title', 'Control de Fumigación')

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

    .fumigacion-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .fumigacion-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .fumigacion-header {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fumigacion-body {
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

    .badge-area {
        display: inline-block;
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
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
        background: #fef3c7;
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .producto-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #dcfce7;
        color: #166534;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
    }

    .cantidad-badge {
        background: #16a34a;
        color: white;
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
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
                        <i class="fas fa-spray-can mr-2"></i>
                        Control de Fumigación
                    </h3>
                    <p class="modern-card-subtitle">
                        Registro y seguimiento de fumigaciones
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.fumigacion.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nueva Fumigación
                        </a>
                    </div>

                    @if($fumigaciones->count() > 0)
                        @foreach($fumigaciones as $f)
                        <div class="fumigacion-card">
                            <div class="fumigacion-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-map-marked-alt mr-2"></i>
                                        <span class="badge-area">{{ $f->area_fumigada }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.fumigacion.edit', $f) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.fumigacion.destroy', $f) }}"
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
                            <div class="fumigacion-body">
                                <!-- Información General -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Fumigación
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $f->fecha_fumigacion->format('d/m/Y') }}
                                            <small>({{ $f->fecha_fumigacion->locale('es')->isoFormat('dddd') }})</small>
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-flask text-success"></i> Producto Utilizado
                                        </span>
                                        <span class="producto-badge">
                                            <i class="fas fa-vial"></i>
                                            {{ $f->producto_utilizado }}
                                            <span class="cantidad-badge">{{ $f->cantidad_producto }}</span>
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-check text-primary"></i> Responsable
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $f->responsable }}
                                        </span>
                                    </div>

                                    @if($f->proxima_fumigacion)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-plus text-warning"></i> Próxima Fumigación
                                        </span>
                                        <span class="date-badge proxima">
                                            <i class="fas fa-calendar-day"></i>
                                            {{ $f->proxima_fumigacion->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                @if($f->observaciones)
                                <div class="info-item mt-3" style="grid-column: 1 / -1;">
                                    <span class="info-label">
                                        <i class="fas fa-sticky-note"></i> Observaciones
                                    </span>
                                    <span class="info-value">{{ $f->observaciones }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $fumigaciones->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-spray-can text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de fumigación.</p>
                            <a href="{{ route('control.fumigacion.create') }}" class="btn-modern btn-primary mt-3">
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

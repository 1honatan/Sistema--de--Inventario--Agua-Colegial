@extends('layouts.app')

@section('title', 'Control Fosa Séptica')

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

    .fosa-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .fosa-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .fosa-header {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fosa-body {
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

    .badge-tipo {
        display: inline-block;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .badge-trabajo {
        display: inline-block;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .date-badge.proxima {
        background: #e0f2fe;
        color: #075985;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
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
                        <i class="fas fa-toilet mr-2"></i>
                        Registro de Limpiezas de Fosa Séptica
                    </h3>
                    <p class="modern-card-subtitle">
                        Gestión y control de mantenimiento de fosas sépticas
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.fosa-septica.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nueva Limpieza
                        </a>
                    </div>

                    @if($registros->count() > 0)
                        @foreach($registros as $r)
                        <div class="fosa-card">
                            <div class="fosa-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-tag mr-2"></i>
                                        <span class="badge-tipo">{{ $r->tipo_fosa }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.fosa-septica.edit', $r) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.fosa-septica.destroy', $r) }}"
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
                            <div class="fosa-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Limpieza
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $r->fecha_limpieza->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-plus text-warning"></i> Próxima Limpieza
                                        </span>
                                        <span class="date-badge proxima">
                                            <i class="fas fa-bell"></i>
                                            {{ $r->proxima_limpieza ? $r->proxima_limpieza->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-tasks text-primary"></i> Tipo de Trabajo
                                        </span>
                                        <span class="badge-trabajo">
                                            {{ $r->detalle_trabajo }}
                                        </span>
                                    </div>
                                </div>

                                <div class="info-grid mt-3">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-check"></i> Responsable
                                        </span>
                                        <span class="info-value">{{ $r->responsable }}</span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-building"></i> Empresa Contratada
                                        </span>
                                        <span class="info-value">{{ $r->empresa_contratada ?? '-' }}</span>
                                    </div>

                                    @if($r->observaciones)
                                    <div class="info-item" style="grid-column: 1 / -1;">
                                        <span class="info-label">
                                            <i class="fas fa-sticky-note"></i> Observaciones
                                        </span>
                                        <span class="info-value">{{ $r->observaciones }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $registros->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de limpieza de fosa séptica.</p>
                            <a href="{{ route('control.fosa-septica.create') }}" class="btn-modern btn-primary mt-3">
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

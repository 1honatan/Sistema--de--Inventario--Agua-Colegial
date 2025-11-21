@extends('layouts.app')

@section('title', 'Control de Producción Diaria')

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

    .produccion-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .produccion-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .produccion-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .produccion-body {
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

    .badge-id {
        display: inline-block;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
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

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
    }

    .producto-item {
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        border-left: 4px solid #4f46e5;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .producto-nombre {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .producto-cantidad {
        font-size: 1.2rem;
        font-weight: 700;
        color: #4f46e5;
    }

    .materiales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
        background: #faf5ff;
        padding: 1rem;
        border-radius: 8px;
    }

    .material-item {
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        border-left: 4px solid #7c3aed;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .material-nombre {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
    }

    .material-cantidad {
        background: #7c3aed;
        color: white;
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .count-badge-productos {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .count-badge-materiales {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        color: #6b21a8;
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
                        <i class="fas fa-industry mr-2"></i>
                        Control de Producción Diaria
                    </h3>
                    <p class="modern-card-subtitle">
                        Registro y gestión de producción diaria
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.produccion.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nuevo Registro
                        </a>
                    </div>

                    @if($producciones->count() > 0)
                        @foreach($producciones as $produccion)
                        <div class="produccion-card">
                            <div class="produccion-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-hashtag mr-2"></i>
                                        <span class="badge-id">Producción #{{ $produccion->id }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.produccion.show', $produccion) }}"
                                       class="btn btn-sm btn-light"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('control.produccion.edit', $produccion) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.produccion.destroy', $produccion) }}"
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
                            <div class="produccion-body">
                                <!-- Información General -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha
                                        </span>
                                        <span class="date-badge">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ $produccion->fecha->format('d/m/Y') }}
                                            <small>({{ $produccion->fecha->locale('es')->isoFormat('dddd') }})</small>
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-tie text-primary"></i> Responsable
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-user-check mr-1"></i> {{ $produccion->responsable ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-boxes text-indigo-600"></i> Total Productos
                                        </span>
                                        <span class="count-badge count-badge-productos">
                                            <i class="fas fa-box"></i>
                                            {{ $produccion->productos->count() }}
                                        </span>
                                    </div>

                                    @if($produccion->materiales->count() > 0)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-tools text-purple-600"></i> Total Materiales
                                        </span>
                                        <span class="count-badge count-badge-materiales">
                                            <i class="fas fa-toolbox"></i>
                                            {{ $produccion->materiales->count() }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Productos Producidos -->
                                @if($produccion->productos->count() > 0)
                                <div class="mt-3">
                                    <span class="info-label mb-2 d-block">
                                        <i class="fas fa-box-open text-indigo-600"></i> Productos Producidos
                                    </span>
                                    <div class="productos-grid">
                                        @foreach($produccion->productos as $producto)
                                        <div class="producto-item">
                                            <div class="producto-nombre">
                                                <i class="fas fa-cube text-indigo-500"></i>
                                                {{ is_object($producto->producto) ? $producto->producto->nombre : $producto->producto }}
                                            </div>
                                            <div class="producto-cantidad">{{ number_format($producto->cantidad) }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Materiales Utilizados -->
                                @if($produccion->materiales->count() > 0)
                                <div class="mt-3">
                                    <span class="info-label mb-2 d-block">
                                        <i class="fas fa-tools text-purple-600"></i> Materiales Utilizados
                                    </span>
                                    <div class="materiales-grid">
                                        @foreach($produccion->materiales as $material)
                                        <div class="material-item">
                                            <span class="material-nombre">
                                                <i class="fas fa-wrench text-purple-500"></i>
                                                {{ $material->nombre_material }}
                                            </span>
                                            <span class="material-cantidad">{{ number_format($material->cantidad) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $producciones->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-industry text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de producción diaria.</p>
                            <a href="{{ route('control.produccion.create') }}" class="btn-modern btn-primary mt-3">
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

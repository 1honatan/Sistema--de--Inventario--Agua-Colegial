@extends('layouts.app')

@section('title', 'Detalle de Producción')

@section('page-subtitle')
Registro #{{ $produccion->id }} - {{ $produccion->fecha->format('d/m/Y') }}
@endsection

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
        min-height: 100vh;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .detail-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: white;
        padding: 2rem;
        border-bottom: 4px solid #1e40af;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .info-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #1e3a8a;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        color: #1e3a8a;
        font-weight: 700;
    }

    .section-title {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: white;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }

    .items-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
        color: #1e3a8a;
        border-bottom: 2px solid #cbd5e1;
    }

    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .items-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-count {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('control.produccion.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>

    <!-- Información General -->
    <div class="detail-card">
        <div class="detail-header">
            <h3 class="mb-0">
                <i class="fas fa-info-circle"></i>
                Información General
            </h3>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-hashtag"></i> ID Registro
                </div>
                <div class="info-value">#{{ $produccion->id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-calendar-alt"></i> Fecha
                </div>
                <div class="info-value">{{ $produccion->fecha->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-user-tie"></i> Responsable
                </div>
                <div class="info-value">{{ $produccion->responsable ?? 'N/A' }}</div>
            </div>
        </div>

        @if($produccion->observaciones)
        <div class="px-4 pb-4">
            <div class="info-item" style="border-left-color: #f59e0b;">
                <div class="info-label">
                    <i class="fas fa-sticky-note"></i> Observaciones
                </div>
                <div class="info-value" style="font-size: 1rem; color: #374151;">
                    {{ $produccion->observaciones }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Productos Producidos -->
    <div class="detail-card">
        <div class="section-title">
            <i class="fas fa-boxes"></i>
            Productos Producidos
            <span class="badge-count ml-auto">{{ $produccion->productos->count() }}</span>
        </div>
        <div class="p-4">
            @if($produccion->productos->count() > 0)
                <div class="table-responsive">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produccion->productos as $index => $item)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <i class="fas fa-box text-primary"></i>
                                    {{ $item->producto->nombre ?? 'Producto eliminado' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        {{ number_format($item->cantidad) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            <tr style="background: #f1f5f9; font-weight: bold;">
                                <td colspan="2" class="text-right">
                                    <i class="fas fa-calculator"></i> TOTAL:
                                </td>
                                <td class="text-center">
                                    <span style="font-size: 1.2rem; color: #1e3a8a;">
                                        {{ number_format($produccion->productos->sum('cantidad')) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No hay productos registrados</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Materiales Utilizados -->
    <div class="detail-card">
        <div class="section-title" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
            <i class="fas fa-tools"></i>
            Materiales Utilizados
            <span class="badge-count ml-auto">{{ $produccion->materiales->count() }}</span>
        </div>
        <div class="p-4">
            @if($produccion->materiales->count() > 0)
                <div class="table-responsive">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Material</th>
                                <th class="text-center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produccion->materiales as $index => $material)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    <i class="fas fa-tools text-purple-600"></i>
                                    {{ $material->nombre_material }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        {{ number_format($material->cantidad) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-toolbox"></i>
                    <p>No hay materiales registrados</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <a href="{{ route('control.produccion.edit', $produccion) }}"
               class="btn btn-warning btn-lg mr-2"
               style="padding: 1rem 2rem;">
                <i class="fas fa-edit"></i> Editar Registro
            </a>
            <a href="{{ route('control.produccion.index') }}"
               class="btn btn-secondary btn-lg"
               style="padding: 1rem 2rem;">
                <i class="fas fa-list"></i> Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection

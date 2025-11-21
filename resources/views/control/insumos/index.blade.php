@extends('layouts.app')

@section('title', 'Control de Insumos')

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

    .insumo-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .insumo-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .insumo-header {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .insumo-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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

    .badge-nombre {
        display: inline-block;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .badge-producto {
        display: inline-block;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge-cantidad {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    .badge-lote {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #e0f2fe;
        color: #075985;
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-vencimiento {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fee2e2;
        color: #991b1b;
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .badge-vencimiento.ok {
        background: #cffafe;
        color: #155e75;
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
                        <i class="fas fa-box-open mr-2"></i>
                        Control de Insumos
                    </h3>
                    <p class="modern-card-subtitle">
                        Gestión y registro de insumos de la empresa
                    </p>
                </div>
                <div class="modern-card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.insumos.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nuevo Insumo
                        </a>
                    </div>

                    @if($insumos->count() > 0)
                        @foreach($insumos as $i)
                        <div class="insumo-card">
                            <div class="insumo-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-cube mr-2"></i>
                                        <span class="badge-nombre">{{ $i->producto_insumo }}</span>
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.insumos.edit', $i) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.insumos.destroy', $i) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de eliminar este insumo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="insumo-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Registro
                                        </span>
                                        <span class="info-value">
                                            {{ $i->fecha->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-balance-scale text-success"></i> Cantidad
                                        </span>
                                        <span class="badge-cantidad">
                                            <i class="fas fa-box"></i>
                                            {{ $i->cantidad }} {{ strtoupper($i->unidad_medida) }}
                                        </span>
                                    </div>

                                    @if($i->numero_lote)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-barcode text-warning"></i> Número de Lote
                                        </span>
                                        <span class="badge-lote">
                                            <i class="fas fa-barcode"></i>
                                            {{ $i->numero_lote }}
                                        </span>
                                    </div>
                                    @endif

                                    @if($i->fecha_vencimiento)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-times text-danger"></i> Fecha de Vencimiento
                                        </span>
                                        @php
                                            $hoy = \Carbon\Carbon::now();
                                            $vencimiento = $i->fecha_vencimiento;
                                            $diasRestantes = $hoy->diffInDays($vencimiento, false);
                                            $vencido = $diasRestantes < 0;
                                            $porVencer = $diasRestantes >= 0 && $diasRestantes <= 30;
                                        @endphp
                                        <span class="badge-vencimiento {{ !$vencido && !$porVencer ? 'ok' : '' }}">
                                            <i class="fas fa-{{ $vencido ? 'exclamation-triangle' : ($porVencer ? 'bell' : 'check') }}"></i>
                                            {{ $vencimiento->format('d/m/Y') }}
                                            @if($vencido)
                                                (Vencido)
                                            @elseif($porVencer)
                                                ({{ abs($diasRestantes) }} días)
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                <div class="info-grid mt-3">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-user-check"></i> Responsable
                                        </span>
                                        <span class="info-value">{{ $i->responsable }}</span>
                                    </div>

                                    @if($i->proveedor)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-truck"></i> Proveedor
                                        </span>
                                        <span class="info-value">{{ $i->proveedor }}</span>
                                    </div>
                                    @endif

                                    @if($i->observaciones)
                                    <div class="info-item" style="grid-column: 1 / -1;">
                                        <span class="info-label">
                                            <i class="fas fa-sticky-note"></i> Observaciones
                                        </span>
                                        <span class="info-value">{{ $i->observaciones }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $insumos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de insumos.</p>
                            <a href="{{ route('control.insumos.create') }}" class="btn-modern btn-primary mt-3">
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

@extends('layouts.app')

@section('title', 'Control de Salidas de Productos')
@section('page-title', 'Control de Salidas de Productos')

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

    .salida-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .salida-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .salida-header {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .salida-body {
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

    .badge-distribuidor {
        display: inline-block;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .badge-producto {
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

    .date-badge.fecha {
        background: #e0f2fe;
        color: #075985;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
    }

    .producto-item {
        text-align: center;
        padding: 0.5rem;
        background: white;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .producto-cantidad {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0284c7;
    }

    .producto-nombre {
        font-size: 0.7rem;
        color: #6b7280;
        text-transform: uppercase;
        margin-top: 0.25rem;
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
                        <i class="fas fa-truck-loading mr-2"></i>
                        Control de Salidas de Productos
                    </h3>
                    <p class="modern-card-subtitle">
                        Registro y gestión de despachos de productos por distribuidor
                    </p>
                </div>
                <div class="modern-card-body">
                    <!-- Navegación de Semanas Mejorada -->
                    <div class="mb-4" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('control.salidas.index', ['semana' => ($semana ?? 0) - 1]) }}"
                               class="btn"
                               style="background: white; color: #0284c7; border: 2px solid #0ea5e9; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2); transition: all 0.3s ease;"
                               onmouseover="this.style.background='#0ea5e9'; this.style.color='white'; this.style.transform='translateX(-5px)'"
                               onmouseout="this.style.background='white'; this.style.color='#0284c7'; this.style.transform='translateX(0)'">
                                <i class="fas fa-chevron-left mr-2"></i>
                                <span>Semana Anterior</span>
                            </a>

                            <div class="text-center" style="background: white; padding: 1rem 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                                <div style="color: #64748b; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">
                                    <i class="fas fa-calendar-week" style="color: #0ea5e9;"></i> Semana Actual
                                </div>
                                <h4 class="mb-0" style="color: #0284c7; font-weight: 800; font-size: 1.25rem; letter-spacing: -0.5px;">
                                    {{ $inicioSemana->format('d/m/Y') }} - {{ $finSemana->format('d/m/Y') }}
                                </h4>
                                <div style="color: #94a3b8; font-size: 0.8rem; margin-top: 0.25rem;">
                                    {{ $inicioSemana->locale('es')->isoFormat('MMMM YYYY') }}
                                </div>
                            </div>

                            <a href="{{ route('control.salidas.index', ['semana' => ($semana ?? 0) + 1]) }}"
                               class="btn"
                               style="background: white; color: #0284c7; border: 2px solid #0ea5e9; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2); transition: all 0.3s ease;"
                               onmouseover="this.style.background='#0ea5e9'; this.style.color='white'; this.style.transform='translateX(5px)'"
                               onmouseout="this.style.background='white'; this.style.color='#0284c7'; this.style.transform='translateX(0)'">
                                <span>Semana Siguiente</span>
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('control.salidas.create') }}" class="btn-modern btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Nueva Salida
                        </a>
                    </div>

                    <!-- Filtro por Tipo de Salida -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('control.salidas.index') }}" class="d-flex align-items-center gap-3">
                            <input type="hidden" name="semana" value="{{ $semana ?? 0 }}">
                            <div class="flex-grow-1" style="max-width: 350px;">
                                <label class="form-label mb-2" style="font-weight: 700; color: #0c4a6e; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-filter" style="color: #0ea5e9; font-size: 1rem;"></i>
                                    Filtrar por Tipo de Salida
                                </label>
                                <select name="tipo_salida"
                                        class="form-select"
                                        style="border: 2px solid #0ea5e9;
                                               border-radius: 12px;
                                               padding: 0.75rem 1rem;
                                               font-weight: 600;
                                               color: #0c4a6e;
                                               background: linear-gradient(to right, #ffffff, #f0f9ff);
                                               box-shadow: 0 2px 8px rgba(14, 165, 233, 0.15);
                                               transition: all 0.3s ease;
                                               cursor: pointer;"
                                        onchange="this.form.submit()"
                                        onfocus="this.style.borderColor='#0284c7'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.25)';"
                                        onblur="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 2px 8px rgba(14, 165, 233, 0.15)';">
                                    <option value="">📋 Todos los tipos</option>
                                    <option value="Despacho Interno" {{ request('tipo_salida') == 'Despacho Interno' ? 'selected' : '' }}>🏭 Despacho Interno</option>
                                    <option value="Pedido Cliente" {{ request('tipo_salida') == 'Pedido Cliente' ? 'selected' : '' }}>📦 Pedido Cliente</option>
                                    <option value="Venta Directa" {{ request('tipo_salida') == 'Venta Directa' ? 'selected' : '' }}>💰 Venta Directa</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($salidas->count() > 0)
                        @foreach($salidas as $salida)
                        <div class="salida-card">
                            <div class="salida-header">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-list mr-2"></i>
                                        <span class="badge badge-primary" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">{{ $salida->tipo_salida ?? 'Sin Tipo' }}</span>

                                        @if($salida->tipo_salida === 'Pedido Cliente')
                                            @if($salida->chofer)
                                            <i class="fas fa-user ml-3 mr-1"></i>
                                            <span class="badge-distribuidor">Chofer: {{ $salida->chofer }}</span>
                                            @endif

                                            @if($salida->nombre_distribuidor && $salida->nombre_distribuidor !== $salida->nombre_cliente)
                                            <i class="fas fa-user-tie ml-3 mr-1"></i>
                                            <span class="badge-distribuidor">Distribuidor: {{ $salida->nombre_distribuidor }}</span>
                                            @endif

                                            @if($salida->nombre_cliente)
                                            <i class="fas fa-store ml-3 mr-1"></i>
                                            <span class="badge-distribuidor">Cliente: {{ $salida->nombre_cliente }}</span>
                                            @endif
                                        @else
                                            @if($salida->chofer && $salida->nombre_distribuidor)
                                            <i class="fas fa-user-tie ml-3 mr-2"></i>
                                            <span class="badge-distribuidor">Chofer: {{ $salida->chofer }}, Distribuidor: {{ $salida->nombre_distribuidor }}</span>
                                            @else
                                            <i class="fas fa-user-tie ml-3 mr-2"></i>
                                            <span class="badge-distribuidor">{{ $salida->nombre_distribuidor }}</span>
                                            @endif
                                        @endif
                                    </h5>
                                </div>
                                <div class="action-buttons">
                                    <a href="{{ route('control.salidas.show', $salida) }}"
                                       class="btn btn-sm btn-light"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('control.salidas.edit', $salida) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('control.salidas.destroy', $salida) }}"
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
                            <div class="salida-body">
                                <!-- Información General -->
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-calendar-alt text-info"></i> Fecha de Salida
                                        </span>
                                        <span class="date-badge fecha">
                                            <i class="fas fa-calendar-check"></i>
                                            {{ \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y') }}
                                            <small>({{ \Carbon\Carbon::parse($salida->fecha)->locale('es')->isoFormat('dddd') }})</small>
                                        </span>
                                    </div>

                                    @if($salida->tipo_salida)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-tag text-success"></i> Tipo de Salida
                                        </span>
                                        <span class="badge-producto">
                                            <i class="fas fa-shipping-fast mr-1"></i> {{ $salida->tipo_salida }}
                                        </span>
                                    </div>
                                    @endif

                                    @if($salida->vehiculo_placa)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-truck text-primary"></i> Vehículo
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-id-card mr-1"></i> {{ $salida->vehiculo_placa }}
                                        </span>
                                    </div>
                                    @endif

                                    @if($salida->hora_llegada)
                                    <div class="info-item">
                                        <span class="info-label">
                                            <i class="fas fa-clock text-warning"></i> Hora de Llegada
                                        </span>
                                        <span class="info-value">
                                            <i class="fas fa-stopwatch mr-1"></i> {{ \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') }}
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Productos Despachados -->
                                <div class="mt-3">
                                    <span class="info-label mb-2 d-block">
                                        <i class="fas fa-boxes text-success"></i> Productos Despachados
                                    </span>
                                    <div class="productos-grid">
                                        @if($salida->botellones > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-bottle-water text-primary" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->botellones }}</div>
                                            <div class="producto-nombre">Botellones</div>
                                        </div>
                                        @endif

                                        @if($salida->retornos > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-undo text-info" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->retornos }}</div>
                                            <div class="producto-nombre">Retornos</div>
                                        </div>
                                        @endif

                                        @if($salida->bolo_grande > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-circle text-purple" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->bolo_grande }}</div>
                                            <div class="producto-nombre">Bolo Grande</div>
                                        </div>
                                        @endif

                                        @if($salida->bolo_pequeño > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-dot-circle text-purple" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->bolo_pequeño }}</div>
                                            <div class="producto-nombre">Bolo Pequeño</div>
                                        </div>
                                        @endif

                                        @if($salida->gelatina > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-cookie-bite text-danger" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->gelatina }}</div>
                                            <div class="producto-nombre">Gelatina</div>
                                        </div>
                                        @endif

                                        @if($salida->agua_saborizada > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-glass-martini text-success" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->agua_saborizada }}</div>
                                            <div class="producto-nombre">Agua Saborizada</div>
                                        </div>
                                        @endif

                                        @if($salida->agua_limon > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-lemon text-warning" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->agua_limon }}</div>
                                            <div class="producto-nombre">Agua Limón</div>
                                        </div>
                                        @endif

                                        @if($salida->agua_natural > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-tint text-info" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->agua_natural }}</div>
                                            <div class="producto-nombre">Agua Natural</div>
                                        </div>
                                        @endif

                                        @if($salida->hielo > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-snowflake text-cyan" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->hielo }}</div>
                                            <div class="producto-nombre">Hielo</div>
                                        </div>
                                        @endif

                                        @if($salida->dispenser > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-water text-primary" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->dispenser }}</div>
                                            <div class="producto-nombre">Dispenser</div>
                                        </div>
                                        @endif

                                        @if($salida->choreados > 0)
                                        <div class="producto-item">
                                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.2rem;"></i>
                                            <div class="producto-cantidad">{{ $salida->choreados }}</div>
                                            <div class="producto-nombre">Choreados</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                @if($salida->observaciones)
                                <div class="info-item mt-3" style="grid-column: 1 / -1;">
                                    <span class="info-label">
                                        <i class="fas fa-sticky-note"></i> Observaciones
                                    </span>
                                    <span class="info-value">{{ $salida->observaciones }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-truck-loading text-gray-300" style="font-size: 4rem;"></i>
                            <p class="mt-3 text-muted">No hay registros de salidas de productos.</p>
                            <a href="{{ route('control.salidas.create') }}" class="btn-modern btn-primary mt-3">
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

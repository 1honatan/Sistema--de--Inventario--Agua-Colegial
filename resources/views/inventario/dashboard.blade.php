@extends('layouts.app')

@section('title', 'Dashboard Inventario')
@section('page-title', 'Dashboard de Inventario')
@section('page-subtitle', 'Gestión y control de stock')

@push('styles')
<style>
    /* Estilos Institucionales Agua Colegial */
    :root {
        --azul-institucional: #1e3a8a;
        --azul-claro: #3b82f6;
        --verde-exito: #059669;
        --rojo-alerta: #dc2626;
        --naranja-warning: #d97706;
    }

    /* Tarjetas KPI Mejoradas */
    .kpi-card {
        background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08),
                    0 4px 12px rgba(0, 0, 0, 0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(255, 255, 255, 0.9);
        border-left: 6px solid;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .kpi-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12),
                    0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .kpi-card.azul { border-left-color: var(--azul-institucional); }
    .kpi-card.verde { border-left-color: var(--verde-exito); }
    .kpi-card.rojo { border-left-color: var(--rojo-alerta); }
    .kpi-card.naranja { border-left-color: var(--naranja-warning); }

    .kpi-icon {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .kpi-card:hover .kpi-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .kpi-icon.azul {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.4);
    }
    .kpi-icon.verde {
        background: linear-gradient(135deg, #059669, #10b981);
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
    }
    .kpi-icon.rojo {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4);
    }
    .kpi-icon.naranja {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.4);
    }

    /* Badges de urgencia */
    .urgencia-critica {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .urgencia-alta {
        background: #fed7aa;
        color: #9a3412;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .urgencia-media {
        background: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Alertas */
    .alerta-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid;
        transition: all 0.2s ease;
    }

    .alerta-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .alerta-card.critica { border-left-color: #dc2626; background: #fef2f2; }
    .alerta-card.alta { border-left-color: #f59e0b; background: #fffbeb; }
    .alerta-card.media { border-left-color: #eab308; background: #fefce8; }

    /* Botones de acceso rápido */
    .btn-accion {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        border: 2px solid transparent;
    }

    .btn-accion:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: var(--azul-institucional);
    }

    .btn-accion i {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .btn-accion.azul i { background: linear-gradient(135deg, #1e3a8a, #3b82f6); }
    .btn-accion.verde i { background: linear-gradient(135deg, #059669, #10b981); }
    .btn-accion.morado i { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .btn-accion.rojo i { background: linear-gradient(135deg, #dc2626, #ef4444); }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- SECCIÓN: Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- KPI: Stock Total --}}
        <div class="kpi-card azul">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Stock Total</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($stockTotal) }}</p>
                    <p class="text-xs text-gray-500 mt-1">unidades disponibles</p>
                </div>
                <div class="kpi-icon azul">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        {{-- KPI: Entradas Hoy --}}
        <div class="kpi-card verde">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Entradas Hoy</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($entradasHoy) }}</p>
                    <p class="text-xs text-gray-500 mt-1">unidades ingresadas</p>
                </div>
                <div class="kpi-icon verde">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>

        {{-- KPI: Salidas Hoy --}}
        <div class="kpi-card rojo">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Salidas Hoy</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($salidasHoy) }}</p>
                    <p class="text-xs text-gray-500 mt-1">unidades retiradas</p>
                </div>
                <div class="kpi-icon rojo">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>

        {{-- KPI: Productos en Riesgo --}}
        <div class="kpi-card naranja">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Productos en Riesgo</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $productosEnRiesgo }}</p>
                    <p class="text-xs text-gray-500 mt-1">stock por debajo del mínimo</p>
                </div>
                <div class="kpi-icon naranja">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Contenido Principal (Tabla + Alertas) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Tabla: Stock por Producto (2/3 del espacio) --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-warehouse text-blue-600"></i>
                    Stock por Producto
                </h2>
            </div>

            {{-- Filtros --}}
            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filtrar por Tipo</label>
                    <select id="filtroTipo" class="w-full border-gray-300 rounded-lg">
                        <option value="">Todos los tipos</option>
                        @foreach($productos->pluck('tipoProducto')->unique()->sortBy('nombre') as $tipo)
                            @if($tipo)
                                <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filtrar por Estado</label>
                    <select id="filtroEstado" class="w-full border-gray-300 rounded-lg">
                        <option value="">Todos los estados</option>
                        <option value="normal">Normal</option>
                        <option value="riesgo">En Riesgo</option>
                    </select>
                </div>
            </div>

            {{-- Tabla DataTables --}}
            <div class="overflow-x-auto">
                <table id="tablaStock" class="display w-full">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Stock Actual</th>
                            <th>Unidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr data-tipo="{{ $producto->tipoProducto->nombre ?? 'Sin tipo' }}"
                                data-estado="{{ $producto->en_riesgo ? 'riesgo' : 'normal' }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $producto->nombre }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $producto->tipoProducto->nombre ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-lg font-bold {{ $producto->en_riesgo ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($producto->stock_actual) }}
                                    </span>
                                </td>
                                <td>{{ $producto->unidad_medida }}</td>
                                <td>
                                    @if($producto->urgencia === 'crítica')
                                        <span class="urgencia-critica">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Crítico
                                        </span>
                                    @elseif($producto->urgencia === 'alta')
                                        <span class="urgencia-alta">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Urgente
                                        </span>
                                    @elseif($producto->urgencia === 'media')
                                        <span class="urgencia-media">
                                            <i class="fas fa-exclamation mr-1"></i>Advertencia
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>Normal
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('inventario.historial', $producto->id) }}"
                                       class="btn btn-secondary btn-sm"
                                       title="Ver movimientos">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Panel: Alertas de Stock (1/3 del espacio) --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3 mb-4">
                <i class="fas fa-bell text-red-600"></i>
                Alertas de Stock
            </h2>

            <div class="space-y-3 max-h-[600px] overflow-y-auto">
                @forelse($alertas as $alerta)
                    <div class="alerta-card {{ $alerta->urgencia }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-sm mb-1">{{ $alerta->nombre }}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <span>Stock: <strong class="text-red-600">{{ $alerta->stock_actual }}</strong></span>
                                    <span>•</span>
                                    <span>Mínimo: {{ $alerta->stock_minimo ?? 10 }}</span>
                                </div>

                                {{-- Nivel de urgencia --}}
                                <div class="mt-2">
                                    @if($alerta->urgencia === 'crítica')
                                        <span class="urgencia-critica">
                                            <i class="fas fa-times-circle mr-1"></i>Crítica
                                        </span>
                                    @elseif($alerta->urgencia === 'alta')
                                        <span class="urgencia-alta">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Alta
                                        </span>
                                    @else
                                        <span class="urgencia-media">
                                            <i class="fas fa-exclamation mr-1"></i>Media
                                        </span>
                                    @endif
                                </div>

                                {{-- Acciones --}}
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('inventario.movimiento.create', ['producto' => $alerta->id]) }}"
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-plus-circle mr-1"></i>Atender
                                    </a>
                                </div>
                            </div>

                            {{-- Icono de alerta --}}
                            <div>
                                @if($alerta->urgencia === 'crítica')
                                    <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                                @elseif($alerta->urgencia === 'alta')
                                    <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                                @else
                                    <i class="fas fa-exclamation text-yellow-600 text-2xl"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-600 text-5xl mb-3"></i>
                        <p class="text-lg font-bold text-gray-900">Stock en Niveles Óptimos</p>
                        <p class="text-sm text-gray-600 mt-2">No hay alertas activas en este momento</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Accesos Rápidos --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Accesos Rápidos</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Registrar Entrada --}}
            <a href="{{ route('inventario.movimiento.create', ['tipo' => 'entrada']) }}" class="btn-accion verde">
                <i class="fas fa-arrow-circle-down"></i>
                <span class="font-bold text-gray-900">Registrar Entrada</span>
                <span class="text-xs text-gray-600">Nueva entrada de stock</span>
            </a>

            {{-- Registrar Salida --}}
            <a href="{{ route('inventario.movimiento.create', ['tipo' => 'salida']) }}" class="btn-accion rojo">
                <i class="fas fa-arrow-circle-up"></i>
                <span class="font-bold text-gray-900">Registrar Salida</span>
                <span class="text-xs text-gray-600">Nueva salida de stock</span>
            </a>

            {{-- Ver Historial --}}
            <a href="{{ route('inventario.movimiento.historial') }}" class="btn-accion morado">
                <i class="fas fa-history"></i>
                <span class="font-bold text-gray-900">Ver Historial</span>
                <span class="text-xs text-gray-600">Movimientos completos</span>
            </a>

            {{-- Generar Reporte --}}
            <a href="{{ route('admin.reportes.inventario') }}" class="btn-accion azul">
                <i class="fas fa-file-pdf"></i>
                <span class="font-bold text-gray-900">Generar Reporte</span>
                <span class="text-xs text-gray-600">PDF/Excel</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#tablaStock').DataTable({
        pageLength: 10,
        order: [[2, 'asc']], // Ordenar por stock actual (menor primero)
        columnDefs: [
            { orderable: false, targets: 5 } // Desactivar ordenamiento en columna Acciones
        ]
    });

    // Filtro por Tipo de Producto
    $('#filtroTipo').on('change', function() {
        var tipo = $(this).val();

        if (tipo === '') {
            // Mostrar todos
            table.rows().every(function() {
                $(this.node()).show();
            });
        } else {
            // Filtrar por tipo
            table.rows().every(function() {
                var row = $(this.node());
                if (row.data('tipo') === tipo) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        table.draw();
    });

    // Filtro por Estado
    $('#filtroEstado').on('change', function() {
        var estado = $(this).val();

        if (estado === '') {
            // Mostrar todos
            table.rows().every(function() {
                $(this.node()).show();
            });
        } else {
            // Filtrar por estado
            table.rows().every(function() {
                var row = $(this.node());
                if (row.data('estado') === estado) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        table.draw();
    });
});
</script>
@endpush

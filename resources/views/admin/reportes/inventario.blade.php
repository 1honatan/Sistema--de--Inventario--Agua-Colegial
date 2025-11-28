@extends('layouts.app')

@section('title', 'Reporte de Inventario')

@section('page-title', 'Reporte de Inventario')
@section('page-subtitle', 'Análisis de stock y movimientos')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('admin.reportes.index') }}" class="text-blue-600 hover:text-blue-800">Reportes</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Inventario</span>
        </nav>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-green-600"></i>
            Filtros de Búsqueda
        </h3>

        <form action="{{ route('admin.reportes.inventario') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Fecha Inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date"
                           name="fecha_inicio"
                           value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Fecha Fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date"
                           name="fecha_fin"
                           value="{{ request('fecha_fin', now()->format('Y-m-d')) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                </div>

                {{-- Tipo de Movimiento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Movimiento</label>
                    <select name="tipo_movimiento" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">
                        <option value="">Todos</option>
                        <option value="entrada" {{ request('tipo_movimiento') == 'entrada' ? 'selected' : '' }}>Entradas</option>
                        <option value="salida" {{ request('tipo_movimiento') == 'salida' ? 'selected' : '' }}>Salidas</option>
                    </select>
                </div>

                {{-- Botón Buscar --}}
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Entradas</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalEntradas ?? 0) }}
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Salidas</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalSalidas ?? 0) }}
                    </p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Stock Actual</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($stockActual ?? 0) }}
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-warehouse text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Movimientos</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalMovimientos ?? 0) }}
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Inventario --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-boxes mr-2 text-green-600"></i>
                Stock Actual por Producto
            </h3>
            <div>
                <a href="{{ route('admin.reportes.inventario.pdf', request()->all()) }}"
                   target="_blank"
                   class="btn btn-danger flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i>
                    PDF
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="tablaInventario">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inventarios ?? [] as $inventario)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $inventario->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inventario->descripcion ?? 'Sin descripción' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ number_format($inventario->stock_actual ?? 0) }} {{ $inventario->unidad_medida ?? 'unidades' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $cantidad = $inventario->stock_actual ?? 0;
                                @endphp
                                @if($cantidad <= 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Sin Stock
                                    </span>
                                @elseif($cantidad <= 50)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Stock Bajo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No hay registros de inventario</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        @if(isset($inventarios) && count($inventarios) > 0)
        $('#tablaInventario').DataTable({
            order: [[1, 'asc']],
            pageLength: 25
        });
        @endif
    });
</script>
@endpush

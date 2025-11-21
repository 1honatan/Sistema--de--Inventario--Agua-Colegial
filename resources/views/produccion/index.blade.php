@extends('layouts.app')

@section('title', 'Producción')
@section('page-title', 'Gestión de Producción')
@section('page-subtitle', 'Registro y control de lotes de producción')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Lotes de Producción</h2>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $producciones->total() }} lotes registrados</p>
        </div>

        <a href="{{ route('produccion.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Registrar Producción
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('produccion.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="producto" class="block text-sm font-medium text-gray-700 mb-2">Producto</label>
                <select name="producto" id="producto" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los productos</option>
                    @foreach($productos ?? [] as $producto)
                        <option value="{{ $producto->id }}" {{ request('producto') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('produccion.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>

        <!-- Filtros activos -->
        @if(request('producto') || request('fecha_desde') || request('fecha_hasta'))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Filtros activos:</p>
                <div class="flex flex-wrap gap-2">
                    @if(request('producto'))
                        @php
                            $productoSeleccionado = $productos->firstWhere('id', request('producto'));
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            <i class="fas fa-box mr-1"></i>
                            {{ $productoSeleccionado->nombre ?? 'Producto' }}
                        </span>
                    @endif
                    @if(request('fecha_desde'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                            <i class="fas fa-calendar mr-1"></i>
                            Desde: {{ \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y') }}
                        </span>
                    @endif
                    @if(request('fecha_hasta'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                            <i class="fas fa-calendar mr-1"></i>
                            Hasta: {{ \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Producción Hoy</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $produccionHoy ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">unidades</p>
                </div>
                <i class="fas fa-calendar-day text-blue-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Este Mes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $produccionMes ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">unidades</p>
                </div>
                <i class="fas fa-calendar-alt text-green-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Lotes Hoy</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lotesHoy ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">lotes</p>
                </div>
                <i class="fas fa-boxes text-purple-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Production Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lote</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($producciones as $produccion)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-blue-600">{{ $produccion->lote }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-box text-gray-400 mr-2"></i>
                                    <span class="font-medium text-gray-900">{{ $produccion->producto->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ number_format($produccion->cantidad) }} unidades
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900">{{ $produccion->personal->nombre_completo ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $produccion->fecha_produccion->format('d/m/Y') }}
                                <br>
                                <span class="text-xs text-gray-500">{{ $produccion->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('produccion.show', $produccion) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">No hay producciones registradas</p>
                                    <p class="text-sm mt-1">Comienza registrando tu primera producción</p>
                                    <a href="{{ route('produccion.create') }}" class="mt-4 bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-semibold transition">
                                        <i class="fas fa-plus mr-2"></i>
                                        Registrar Producción
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($producciones->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $producciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Historial de Movimientos')
@section('page-title', 'Historial de Movimientos')
@section('page-subtitle', 'Registro completo de entradas y salidas de inventario')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtrar Movimientos
        </h3>

        <form method="GET" action="{{ route('inventario.movimiento.historial') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por Producto -->
            <div>
                <label for="id_producto" class="block text-sm font-semibold text-gray-700 mb-2">
                    Producto
                </label>
                <select name="id_producto" id="id_producto" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los productos</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ request('id_producto') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Tipo de Movimiento -->
            <div>
                <label for="tipo_movimiento" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipo de Movimiento
                </label>
                <select name="tipo_movimiento" id="tipo_movimiento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('tipo_movimiento') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="salida" {{ request('tipo_movimiento') === 'salida' ? 'selected' : '' }}>Salida</option>
                </select>
            </div>

            <!-- Fecha Inicio -->
            <div>
                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha Inicio
                </label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Fecha Fin -->
            <div>
                <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha Fin
                </label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Botones de Acción -->
            <div class="md:col-span-4 flex items-center gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Aplicar Filtros
                </button>
                <a href="{{ route('inventario.movimiento.historial') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-history text-gray-600 mr-2"></i>
                Movimientos Registrados
            </h3>
            <span class="text-sm text-gray-600 font-semibold">
                Total: {{ $movimientos->total() }} registro(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $movimiento->fecha_movimiento ? \Carbon\Carbon::parse($movimiento->fecha_movimiento)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-box text-blue-600"></i>
                                    <span class="font-semibold text-gray-900">{{ $movimiento->producto->nombre ?? 'N/A' }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $movimiento->producto->tipo ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($movimiento->tipo_movimiento === 'entrada')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center">
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Entrada
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Salida
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="font-bold {{ $movimiento->tipo_movimiento === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movimiento->tipo_movimiento === 'entrada' ? '+' : '-' }}{{ $movimiento->cantidad ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $movimiento->observacion ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">No hay movimientos registrados</p>
                                    <p class="text-sm mt-1">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($movimientos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $movimientos->links() }}
            </div>
        @endif
    </div>

    <!-- Botones de Acción -->
    <div class="flex items-center gap-4">
        <a href="{{ route('inventario.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Inventario
        </a>
        <a href="{{ route('inventario.movimiento.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition inline-flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>
            Registrar Movimiento
        </a>
    </div>
</div>
@endsection

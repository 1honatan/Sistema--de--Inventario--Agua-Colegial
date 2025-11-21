@extends('layouts.app')

@section('title', 'Historial de ' . $producto->nombre)
@section('page-title', 'Historial de Movimientos')
@section('page-subtitle', $producto->nombre . ' - ' . $producto->tipo)

@section('content')
<div class="space-y-6">
    <!-- Información del Producto -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-box text-blue-600 mr-2"></i>
                    {{ $producto->nombre }}
                </h3>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span><strong>Tipo:</strong> {{ $producto->tipo }}</span>
                    <span><strong>Presentación:</strong> {{ $producto->presentacion }}</span>
                    <span><strong>Stock Actual:</strong>
                        <span class="font-bold {{ $stockActual > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($stockActual) }} unidades
                        </span>
                    </span>
                </div>
            </div>
            <a href="{{ route('inventario.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver al Inventario
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtrar Movimientos
        </h3>

        <form method="GET" action="{{ route('inventario.historial', $producto) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

            <!-- Botón Filtrar -->
            <div class="flex items-end md:col-span-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition w-full md:w-auto">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                @if(request()->hasAny(['tipo_movimiento', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('inventario.historial', $producto) }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-blue-600 mr-2"></i>
            Historial de Movimientos
        </h3>

        @if($movimientos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Responsable</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($movimientos as $movimiento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($movimiento->fecha_movimiento)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($movimiento->tipo_movimiento === 'entrada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i>
                                            Entrada
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i>
                                            Salida
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $movimiento->tipo_movimiento === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movimiento->tipo_movimiento === 'entrada' ? '+' : '-' }}{{ number_format($movimiento->cantidad) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $movimiento->usuario->personal->nombre_completo ?? ($movimiento->usuario->nombre ?? 'Sistema') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $movimiento->observacion ?? $movimiento->referencia ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $movimientos->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron movimientos para este producto.</p>
                @if(request()->hasAny(['tipo_movimiento', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('inventario.historial', $producto) }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700 font-semibold">
                        Limpiar filtros
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

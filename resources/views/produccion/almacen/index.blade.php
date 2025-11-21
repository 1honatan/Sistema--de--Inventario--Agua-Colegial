@extends('layouts.app')

@section('title', 'Almacén')
@section('page-title', 'Gestión de Almacén')
@section('page-subtitle', 'Ver y gestionar todos los productos del almacén')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Productos en Almacén</h2>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $productos->count() }} productos</p>
        </div>

        <a href="{{ route('almacen.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Agregar Producto
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('almacen.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Producto</label>
                <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                            {{ ucfirst($tipo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('almacen.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($productos as $producto)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <!-- Imagen del Producto -->
                <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-box text-gray-400 text-6xl"></i>
                    @endif
                </div>

                <!-- Info del Producto -->
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $producto->nombre }}</h3>
                            <p class="text-xs text-gray-600">{{ $producto->tipoProducto->nombre ?? $producto->tipo }}</p>
                        </div>
                        @if($producto->estado === 'activo')
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">Activo</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-semibold">Inactivo</span>
                        @endif
                    </div>

                    <!-- Stock -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <p class="text-xs text-gray-500 mb-1">Stock Disponible</p>
                        <p class="text-2xl font-bold {{ $producto->stock_disponible < 50 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($producto->stock_disponible) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $producto->unidad_medida ?? 'unidades' }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('almacen.ajustar-stock', $producto) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded font-semibold transition text-center"
                           title="Ajustar Stock">
                            <i class="fas fa-plus-minus"></i>
                        </a>

                        <a href="{{ route('almacen.edit', $producto) }}"
                           class="bg-yellow-600 hover:bg-yellow-700 text-white text-xs px-3 py-2 rounded font-semibold transition text-center"
                           title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('almacen.destroy', $producto) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded font-semibold transition"
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600 text-lg font-semibold">No hay productos en el almacén</p>
                <p class="text-gray-500 text-sm mt-2">Comienza agregando tu primer producto</p>
                <a href="{{ route('almacen.create') }}" class="mt-4 inline-block bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar Producto
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection

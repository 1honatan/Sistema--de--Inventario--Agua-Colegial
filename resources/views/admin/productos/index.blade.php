@extends('layouts.app')

@section('title', 'Productos')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Catálogo de Productos</h2>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $productos->total() }} productos</p>
        </div>

        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Nuevo Producto
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.productos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="tipo_producto" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Producto</label>
                <select name="tipo_producto" id="tipo_producto" class="select2-no-search">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Models\TipoProducto::where('estado', 'activo')->orderBy('nombre')->get() as $tipo)
                        <option value="{{ $tipo->id }}" {{ request('tipo_producto') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado" class="select2-no-search">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div>
                <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Nombre del producto..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.productos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($productos as $producto)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <!-- Header with Image or Icon -->
                <div class="relative h-48 bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center overflow-hidden">
                    @if($producto->imagen && file_exists(public_path($producto->imagen)))
                        <img src="{{ asset($producto->imagen) }}"
                             alt="{{ $producto->nombre }}"
                             class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-box text-7xl text-blue-300"></i>
                    @endif

                    <div class="absolute top-3 right-3">
                        @if($producto->estado === 'activo')
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i>
                                Activo
                            </span>
                        @else
                            <span class="badge badge-danger">
                                <i class="fas fa-ban mr-1"></i>
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $producto->nombre }}</h3>
                        <p class="text-sm text-gray-600">{{ $producto->descripcion ?? 'Sin descripción' }}</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fas fa-tags mr-1"></i>
                                Categoría:
                            </span>
                            <span class="font-semibold text-gray-900">
                                @if($producto->tipoProducto)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $producto->tipoProducto->nombre }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Sin categoría</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fas fa-balance-scale mr-1"></i>
                                Unidad:
                            </span>
                            <span class="font-semibold text-gray-900">{{ $producto->unidad_medida ?? 'Unidad' }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fas fa-warehouse mr-1"></i>
                                Stock Actual:
                            </span>
                            @php
                                $stock = \App\Models\Inventario::stockDisponible($producto->id);
                                $nivel = $stock > 100 ? 'green' : ($stock > 50 ? 'yellow' : 'red');
                            @endphp
                            <span class="bg-{{ $nivel }}-100 text-{{ $nivel }}-800 px-2 py-1 rounded font-bold">
                                {{ number_format($stock) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-primary flex-1 text-center text-sm">
                            <i class="fas fa-edit"></i>
                            Editar
                        </a>

                        <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="flex-1" id="form-delete-{{ $producto->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmarEliminacion({{ $producto->id }})" class="btn btn-danger w-full text-sm">
                                <i class="fas fa-trash"></i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                    <p class="text-lg font-semibold text-gray-700">No hay productos registrados</p>
                    <p class="text-sm text-gray-500 mt-2">Comienza agregando tu primer producto</p>
                    <a href="{{ route('admin.productos.create') }}" class="mt-6 inline-block bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Producto
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($productos->hasPages())
        <div class="flex justify-center">
            {{ $productos->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmarEliminacion(id) {
    confirmDelete('¿Está seguro de eliminar este producto?').then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-delete-' + id).submit();
        }
    });
}
</script>
@endpush

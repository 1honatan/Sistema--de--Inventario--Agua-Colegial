@extends('layouts.app')

@section('title', 'Tipos de Producto')

@section('page-title', 'Tipos de Producto')
@section('page-subtitle', 'Gestión de tipos de producto del sistema')

@section('content')
<div class="container mx-auto px-4" style="background-color: #c0eaff20; min-height: 100vh; padding: 2rem;">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fa-solid fa-home mr-1"></i>Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600"><i class="fa-solid fa-boxes mr-1"></i>Tipos de Producto</span>
        </nav>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-500 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Tipos</p>
                    <p class="text-3xl font-bold">{{ $totalTipos }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-tags text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-500 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Activos</p>
                    <p class="text-3xl font-bold">{{ $totalActivos }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-500 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Inactivos</p>
                    <p class="text-3xl font-bold">{{ $totalInactivos }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Acciones y Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Botón Crear --}}
            <a href="{{ route('admin.tipos-producto.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus-circle"></i>
                Nuevo Tipo de Producto
            </a>

            {{-- Filtros --}}
            <form method="GET" action="{{ route('admin.tipos-producto.index') }}" class="flex flex-col md:flex-row gap-3 flex-1 md:ml-4">
                {{-- Búsqueda --}}
                <div class="flex-1">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por nombre o código..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Estado --}}
                <select name="estado" class="select2-no-search">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>

                {{-- Botones --}}
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i>Buscar
                </button>

                @if(request('buscar') || request('estado'))
                    <a href="{{ route('admin.tipos-producto.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-times"></i>Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabla de Tipos de Producto --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($tiposProducto->count() > 0)
            <div class="overflow-x-auto">
                <table id="tabla-tipos-producto" class="display" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Productos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tiposProducto as $tipo)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-mono font-semibold bg-blue-100 text-blue-800">
                                        {{ $tipo->codigo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $tipo->nombre }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 max-w-xs truncate" title="{{ $tipo->descripcion }}">
                                        {{ $tipo->descripcion ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-box text-gray-400"></i>
                                        <span>{{ $tipo->cantidadProductos() }}</span>
                                        @if($tipo->cantidadProductosActivos() > 0)
                                            <span class="text-xs text-green-600">({{ $tipo->cantidadProductosActivos() }} activos)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($tipo->estado === 'activo')
                                        <span class="badge badge-success">
                                            <i class="fa-solid fa-check-circle mr-1"></i>
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fa-solid fa-times-circle mr-1"></i>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('admin.tipos-producto.edit', $tipo) }}" class="btn btn-warning text-xs" title="Editar">
                                            <i class="fa-solid fa-edit"></i>
                                            Editar
                                        </a>

                                        @if($tipo->estado === 'activo')
                                            {{-- Desactivar --}}
                                            <form method="POST" action="{{ route('admin.tipos-producto.destroy', $tipo) }}" class="inline" id="form-desactivar-{{ $tipo->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmarDesactivacion({{ $tipo->id }}, '{{ $tipo->nombre }}', {{ $tipo->cantidadProductosActivos() }})"
                                                        class="btn btn-danger text-xs"
                                                        title="Desactivar">
                                                    <i class="fa-solid fa-ban"></i>
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            {{-- Activar --}}
                                            <form method="POST" action="{{ route('admin.tipos-producto.activar', $tipo) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success text-xs" title="Activar">
                                                    <i class="fa-solid fa-check"></i>
                                                    Activar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tiposProducto->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tipos de producto</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Comienza creando un nuevo tipo de producto.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.tipos-producto.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Tipo de Producto
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#tabla-tipos-producto').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[1, 'asc']], // Ordenar por nombre
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: 5 } // Deshabilitar orden en columna "Acciones"
        ]
    });
});

// Confirmación para desactivar tipo de producto
function confirmarDesactivacion(id, nombre, productosActivos) {
    let mensaje = '¿Está seguro de desactivar el tipo "' + nombre + '"?';

    if (productosActivos > 0) {
        mensaje = 'Este tipo de producto tiene ' + productosActivos + ' producto(s) activo(s) asociado(s). ' + mensaje;
    }

    confirmDelete(mensaje).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-desactivar-' + id).submit();
        }
    });
}
</script>
@endpush

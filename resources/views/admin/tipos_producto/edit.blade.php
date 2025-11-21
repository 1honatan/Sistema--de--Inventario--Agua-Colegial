@extends('layouts.app')

@section('title', 'Editar Tipo de Producto')

@section('page-title', 'Editar Tipo de Producto')
@section('page-subtitle', 'Modificar información del tipo de producto')

@section('content')
<div class="max-w-3xl mx-auto px-4" style="background-color: #c0eaff20; min-height: 100vh; padding: 2rem;">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm" style="color: #333333;">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-800" style="color: #1e3a8a;">
                <i class="fa-solid fa-home mr-1"></i>Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('admin.tipos-producto.index') }}" class="hover:text-blue-800" style="color: #1e3a8a;">
                <i class="fa-solid fa-boxes mr-1"></i>Tipos de Producto
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600"><i class="fa-solid fa-edit mr-1"></i>Editar: {{ $tipoProducto->nombre }}</span>
        </nav>
    </div>

    {{-- Información del Tipo --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm text-blue-800">
                    Este tipo tiene <strong>{{ $tipoProducto->cantidadProductos() }}</strong> producto(s) asociado(s)
                    @if($tipoProducto->cantidadProductosActivos() > 0)
                        (<strong>{{ $tipoProducto->cantidadProductosActivos() }}</strong> activo(s))
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.tipos-producto.update', $tipoProducto) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Tipo <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre', $tipoProducto->nombre) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                       placeholder="Ej: Botellón, Bolsa, Saborizada"
                       required
                       autofocus>
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Nombre descriptivo del tipo de producto (máximo 100 caracteres)
                </p>
            </div>

            {{-- Código --}}
            <div class="mb-6">
                <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                    Código <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="codigo"
                       name="codigo"
                       value="{{ old('codigo', $tipoProducto->codigo) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono @error('codigo') border-red-500 @enderror"
                       placeholder="Ej: BOT, BOL, SAB"
                       maxlength="20"
                       style="text-transform: uppercase;"
                       required>
                @error('codigo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Código único de identificación (máximo 20 caracteres, solo mayúsculas, números y guiones)
                </p>
            </div>

            {{-- Descripción --}}
            <div class="mb-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción <span class="text-gray-400">(Opcional)</span>
                </label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="3"
                          maxlength="500"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                          placeholder="Descripción detallada del tipo de producto...">{{ old('descripcion', $tipoProducto->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Máximo 500 caracteres
                </p>
            </div>

            {{-- Estado --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition @error('estado') border-red-500 @else border-gray-300 @enderror">
                        <input type="radio"
                               name="estado"
                               value="activo"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                               {{ old('estado', $tipoProducto->estado) == 'activo' ? 'checked' : '' }}
                               required>
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Activo</span>
                            <span class="block text-xs text-gray-500">Disponible para uso</span>
                        </div>
                        <svg class="absolute right-4 w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition @error('estado') border-red-500 @else border-gray-300 @enderror">
                        <input type="radio"
                               name="estado"
                               value="inactivo"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                               {{ old('estado', $tipoProducto->estado) == 'inactivo' ? 'checked' : '' }}
                               required>
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Inactivo</span>
                            <span class="block text-xs text-gray-500">No disponible</span>
                        </div>
                        <svg class="absolute right-4 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </label>
                </div>
                @error('estado')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if($tipoProducto->cantidadProductosActivos() > 0 && old('estado', $tipoProducto->estado) == 'activo')
                    <p class="mt-2 text-sm text-yellow-600">
                        ⚠️ Advertencia: Hay productos activos asociados. Desactivar este tipo puede afectar el sistema.
                    </p>
                @endif
            </div>

            {{-- Información Adicional --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Información importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Modificar el código afectará las referencias en el sistema</li>
                                <li>Desactivar un tipo con productos activos puede generar inconsistencias</li>
                                <li>Los cambios se aplicarán inmediatamente</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.tipos-producto.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-times"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Información de Auditoría --}}
    <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="font-medium">Creado:</span>
                {{ $tipoProducto->created_at->format('d/m/Y H:i') }}
            </div>
            <div>
                <span class="font-medium">Última actualización:</span>
                {{ $tipoProducto->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilo para radio buttons seleccionados */
    input[type="radio"]:checked + div {
        font-weight: 600;
    }

    input[type="radio"]:checked ~ svg {
        opacity: 1;
    }

    input[type="radio"]:not(:checked) ~ svg {
        opacity: 0.3;
    }

    /* Convertir a mayúsculas automáticamente */
    #codigo {
        text-transform: uppercase;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Advertencia si hay productos activos y se intenta desactivar
    const estadoRadios = document.querySelectorAll('input[name="estado"]');
    const productosActivos = {{ $tipoProducto->cantidadProductosActivos() }};

    if (productosActivos > 0) {
        estadoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'inactivo') {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Atención!',
                        html: `
                            <p class="text-gray-700 mb-3">
                                Este tipo de producto tiene <strong>${productosActivos}</strong> producto(s) activo(s) asociado(s).
                            </p>
                            <p class="text-gray-700">
                                Desactivar este tipo puede afectar el funcionamiento del sistema.
                            </p>
                        `,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#1e3a8a'
                    });
                }
            });
        });
    }
});
</script>
@endpush

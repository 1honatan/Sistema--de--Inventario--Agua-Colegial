@extends('layouts.app')

@section('title', 'Editar Producción')
@section('page-title', 'Editar Producción')
@section('page-subtitle', 'Modifique los datos del registro de producción #' . $produccion->id)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.produccion.index') }}" class="text-cyan-600 hover:text-cyan-800">Producción</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar #{{ $produccion->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.produccion.update', $produccion) }}" method="POST" id="produccionForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información General --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                        Información General
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Fecha --}}
                        <div>
                            <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                                Fecha <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="fecha"
                                   id="fecha"
                                   value="{{ old('fecha', $produccion->fecha->format('Y-m-d')) }}"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha') border-red-500 @enderror">
                            @error('fecha')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Responsable --}}
                        <div>
                            <label for="responsable" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-cyan-600 mr-1"></i>
                                Responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="responsable"
                                    id="responsable"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('responsable') border-red-500 @enderror">
                                <option value="">Seleccione un responsable...</option>
                                @foreach($personal ?? [] as $persona)
                                    <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $produccion->responsable) == $persona->nombre_completo ? 'selected' : '' }}>
                                        {{ $persona->nombre_completo }} ({{ $persona->cargo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('responsable')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="gasto_material" value="0">
                </div>

                {{-- Productos Producidos --}}
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-boxes text-green-600 mr-2"></i>
                            Productos Producidos
                        </h3>
                        <button type="button" onclick="agregarProducto()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-plus mr-2"></i>Agregar Producto
                        </button>
                    </div>

                    <div id="productos-container" class="space-y-4">
                        @foreach($produccion->productos as $index => $producto)
                        <div class="producto-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-green-400 transition">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-box text-green-600 mr-1"></i> Producto
                                    </label>
                                    <select name="productos[{{ $index }}][producto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($productos ?? [] as $prod)
                                            <option value="{{ $prod->nombre }}" {{ ($producto->producto->nombre ?? '') == $prod->nombre ? 'selected' : '' }}>
                                                {{ $prod->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-sort-numeric-up text-green-600 mr-1"></i> Cantidad
                                    </label>
                                    <input type="number"
                                           name="productos[{{ $index }}][cantidad]"
                                           value="{{ $producto->cantidad }}"
                                           min="0"
                                           data-product-qty
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-xl font-bold focus:ring-2 focus:ring-green-500"
                                           required>
                                </div>
                                <div class="md:col-span-2">
                                    <button type="button" onclick="eliminarItem(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Materiales Utilizados --}}
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-tools text-purple-600 mr-2"></i>
                            Materiales Utilizados
                        </h3>
                        <button type="button" onclick="agregarMaterial()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-plus mr-2"></i>Agregar Material
                        </button>
                    </div>

                    <div id="materiales-container" class="space-y-4">
                        @foreach($produccion->materiales as $index => $material)
                        <div class="material-row bg-purple-50 border-2 border-purple-200 rounded-lg p-4 hover:border-purple-400 transition">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tools text-purple-600 mr-1"></i> Material
                                    </label>
                                    <select name="materiales[{{ $index }}][material]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500">
                                        <option value="">Seleccione...</option>
                                        <option value="Bolsa para empaquetar" {{ $material->nombre_material == 'Bolsa para empaquetar' ? 'selected' : '' }}>Bolsa para empaquetar</option>
                                        <option value="Etiquetas para botellones" {{ $material->nombre_material == 'Etiquetas para botellones' ? 'selected' : '' }}>Etiquetas para botellones</option>
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-sort-numeric-up text-purple-600 mr-1"></i> Cantidad
                                    </label>
                                    <input type="number"
                                           name="materiales[{{ $index }}][cantidad]"
                                           value="{{ $material->cantidad }}"
                                           min="0"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-xl font-bold focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div class="md:col-span-2">
                                    <button type="button" onclick="eliminarItem(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total de Producción --}}
                <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-lg p-6 text-center text-white">
                    <p class="text-sm font-medium opacity-90 mb-1">Total de Productos Producidos</p>
                    <h3 id="totalProduccion" class="text-4xl font-bold">0</h3>
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-cyan-600 mr-1"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('observaciones') border-red-500 @enderror"
                              placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones', $produccion->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.produccion.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i>
                            Restablecer
                        </button>

                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Producción
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productoIndex = {{ $produccion->productos->count() }};
let materialIndex = {{ $produccion->materiales->count() }};

$(document).ready(function() {
    calcularTotal();

    $(document).on('input', '[data-product-qty]', calcularTotal);
    $(document).on('click', 'input[type="number"]', function() { $(this).select(); });
});

function calcularTotal() {
    let total = 0;
    $('[data-product-qty]').each(function() {
        total += parseInt($(this).val()) || 0;
    });
    $('#totalProduccion').text(total.toLocaleString());
}

function agregarProducto() {
    const html = `
        <div class="producto-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-green-400 transition">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-box text-green-600 mr-1"></i> Producto
                    </label>
                    <select name="productos[${productoIndex}][producto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500" required>
                        <option value="">Seleccione...</option>
                        @foreach($productos ?? [] as $producto)
                            <option value="{{ $producto->nombre }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sort-numeric-up text-green-600 mr-1"></i> Cantidad
                    </label>
                    <input type="number" name="productos[${productoIndex}][cantidad]" value="0" min="0" data-product-qty class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-xl font-bold focus:ring-2 focus:ring-green-500" required>
                </div>
                <div class="md:col-span-2">
                    <button type="button" onclick="eliminarItem(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#productos-container').append(html);
    productoIndex++;
}

function agregarMaterial() {
    const html = `
        <div class="material-row bg-purple-50 border-2 border-purple-200 rounded-lg p-4 hover:border-purple-400 transition">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tools text-purple-600 mr-1"></i> Material
                    </label>
                    <select name="materiales[${materialIndex}][material]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccione...</option>
                        <option value="Bolsa para empaquetar">Bolsa para empaquetar</option>
                        <option value="Etiquetas para botellones">Etiquetas para botellones</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sort-numeric-up text-purple-600 mr-1"></i> Cantidad
                    </label>
                    <input type="number" name="materiales[${materialIndex}][cantidad]" value="0" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-xl font-bold focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="md:col-span-2">
                    <button type="button" onclick="eliminarItem(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#materiales-container').append(html);
    materialIndex++;
}

function eliminarItem(btn) {
    $(btn).closest('.producto-row, .material-row').fadeOut(300, function() {
        $(this).remove();
        calcularTotal();
    });
}
</script>
@endpush

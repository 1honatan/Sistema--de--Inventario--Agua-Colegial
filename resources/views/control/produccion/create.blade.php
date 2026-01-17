@extends('layouts.app')

@section('title', 'Registrar Producción')
@section('page-title', 'Registrar Producción Diaria')
@section('page-subtitle', 'Complete el formulario para registrar la producción del día')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.produccion.index') }}" class="text-cyan-600 hover:text-cyan-800">Producción</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Registrar</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.produccion.store') }}" method="POST" id="produccionForm">
            @csrf

            <div class="space-y-6">
                {{-- Fecha --}}
                <div>
                    <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="fecha"
                           id="fecha"
                           value="{{ old('fecha', date('Y-m-d')) }}"
                           readonly
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed @error('fecha') border-red-500 @enderror">
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
                    @if(auth()->user()->rol->nombre !== 'admin')
                        {{-- Para empleados: campo bloqueado con su nombre --}}
                        <input type="text"
                               value="{{ auth()->user()->nombre }}"
                               readonly
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        <input type="hidden" name="responsable" value="{{ auth()->user()->nombre }}">
                    @else
                        {{-- Para admin: puede seleccionar cualquier responsable --}}
                        <select name="responsable"
                                id="responsable"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('responsable') border-red-500 @enderror">
                            <option value="">Seleccione un responsable...</option>
                            @foreach($personal ?? [] as $persona)
                                <option value="{{ $persona->nombre_completo }}"
                                        {{ old('responsable') == $persona->nombre_completo ? 'selected' : '' }}>
                                    {{ $persona->nombre_completo }} ({{ $persona->cargo }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('responsable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gasto Material (Campo oculto) --}}
                <input type="hidden" name="gasto_material" value="0">

                {{-- Separador --}}
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-boxes text-cyan-600 mr-2"></i>
                            Productos Producidos
                        </h3>
                        <button type="button" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold transition" onclick="agregarProducto()">
                            <i class="fas fa-plus mr-1"></i>
                            Agregar
                        </button>
                    </div>

                    <div id="productos-container" class="space-y-4">
                        {{-- Producto inicial --}}
                        <div class="item-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-cyan-400 transition" data-index="0">
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-box text-cyan-600 mr-1"></i>
                                        Producto <span class="text-red-500">*</span>
                                    </label>
                                    <select name="productos[0][producto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($productos ?? [] as $producto)
                                            <option value="{{ $producto->nombre }}">
                                                {{ $producto->nombre }}{{ $producto->unidades_por_paquete ? ' (' . $producto->unidades_por_paquete . ' uds/paq)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-sort-numeric-up text-cyan-600 mr-1"></i>
                                        Cantidad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           name="productos[0][cantidad]"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                           min="0"
                                           value="0"
                                           data-product-qty
                                           required>
                                </div>
                                <div class="col-span-2">
                                    <button type="button"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition"
                                            onclick="eliminarItem(this)"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total de Producción --}}
                <div class="bg-gradient-to-r from-cyan-500 to-teal-500 rounded-lg p-6 text-center text-white">
                    <p class="text-sm font-medium opacity-90 mb-1">Total de Productos Producidos</p>
                    <h3 id="totalProduccion" class="text-4xl font-bold transition-transform">0</h3>
                </div>

                {{-- Materiales Utilizados --}}
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-tools text-purple-600 mr-2"></i>
                            Materiales Utilizados
                        </h3>
                        <button type="button" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold transition" onclick="agregarMaterial()">
                            <i class="fas fa-plus mr-1"></i>
                            Agregar
                        </button>
                    </div>

                    <div id="materiales-container" class="space-y-4">
                        {{-- Material inicial --}}
                        <div class="item-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-purple-400 transition" data-index="0">
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tools text-purple-600 mr-1"></i>
                                        Material
                                    </label>
                                    <select name="materiales[0][material]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Seleccione...</option>
                                        <option value="Bolsa para empaquetar">Bolsa para empaquetar</option>
                                        <option value="Etiquetas para botellones">Etiquetas para botellones</option>
                                    </select>
                                </div>
                                <div class="col-span-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-sort-numeric-up text-purple-600 mr-1"></i>
                                        Cantidad
                                    </label>
                                    <input type="number"
                                           name="materiales[0][cantidad]"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                           min="0"
                                           value="0">
                                </div>
                                <div class="col-span-2">
                                    <button type="button"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition"
                                            onclick="eliminarItem(this)"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-cyan-600 mr-1"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('observaciones') border-red-500 @enderror"
                              placeholder="Ingrese observaciones adicionales...">{{ old('observaciones') }}</textarea>
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
                            Limpiar
                        </button>

                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Producción
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
    let productoIndex = 1;
    let materialIndex = 1;

    $(document).ready(function() {
        // Calcular total dinámico
        function calcularTotal() {
            let total = 0;
            $('[data-product-qty]').each(function() {
                total += parseInt($(this).val()) || 0;
            });

            $('#totalProduccion').css('transform', 'scale(1.2)');
            setTimeout(() => {
                $('#totalProduccion').text(total.toLocaleString());
                $('#totalProduccion').css('transform', 'scale(1)');
            }, 150);
        }

        // Event listeners para cálculo
        $(document).on('input', '[data-product-qty]', calcularTotal);

        // Calcular inicial
        calcularTotal();
    });

    // Agregar Producto
    function agregarProducto() {
        const html = `
            <div class="item-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-cyan-400 transition" data-index="${productoIndex}">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-box text-cyan-600 mr-1"></i>
                            Producto <span class="text-red-500">*</span>
                        </label>
                        <select name="productos[${productoIndex}][producto]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos ?? [] as $producto)
                                <option value="{{ $producto->nombre }}">
                                    {{ $producto->nombre }}{{ $producto->unidades_por_paquete ? ' (' . $producto->unidades_por_paquete . ' uds/paq)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-cyan-600 mr-1"></i>
                            Cantidad <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="productos[${productoIndex}][cantidad]" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center focus:ring-2 focus:ring-cyan-500 focus:border-transparent" min="0" value="0" data-product-qty required>
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition" onclick="eliminarItem(this)" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#productos-container').append(html);
        productoIndex++;
    }

    // Agregar Material
    function agregarMaterial() {
        const html = `
            <div class="item-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-purple-400 transition" data-index="${materialIndex}">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tools text-purple-600 mr-1"></i>
                            Material
                        </label>
                        <select name="materiales[${materialIndex}][material]" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Seleccione...</option>
                            <option value="Bolsa para empaquetar">Bolsa para empaquetar</option>
                            <option value="Etiquetas para botellones">Etiquetas para botellones</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-purple-600 mr-1"></i>
                            Cantidad
                        </label>
                        <input type="number" name="materiales[${materialIndex}][cantidad]" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center focus:ring-2 focus:ring-purple-500 focus:border-transparent" min="0" value="0">
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg transition" onclick="eliminarItem(this)" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#materiales-container').append(html);
        materialIndex++;
    }

    // Eliminar Item
    function eliminarItem(btn) {
        $(btn).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            // Recalcular total
            let total = 0;
            $('[data-product-qty]').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#totalProduccion').text(total.toLocaleString());
        });
    }
</script>
@endpush

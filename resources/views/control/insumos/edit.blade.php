@extends('layouts.app')

@section('title', 'Editar Control de Insumo')
@section('page-title', 'Editar Control de Insumo')
@section('page-subtitle', 'Modifique los datos del registro de insumo #' . $insumo->id)

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.insumos.index') }}" class="text-cyan-600 hover:text-cyan-800">Insumos</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar Insumo #{{ $insumo->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.insumos.update', $insumo) }}" method="POST" id="insumoForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información Básica --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fecha --}}
                    <div>
                        <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                            Fecha de Registro
                        </label>
                        <input type="date"
                               name="fecha"
                               id="fecha"
                               value="{{ old('fecha', $insumo->fecha->format('Y-m-d')) }}"
                               readonly
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Fecha no modificable
                        </p>
                    </div>

                    {{-- Producto del Insumo --}}
                    <div>
                        <label for="producto_insumo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-cube text-cyan-600 mr-1"></i>
                            Producto del Insumo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="producto_insumo"
                               id="producto_insumo"
                               value="{{ old('producto_insumo', $insumo->producto_insumo) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('producto_insumo') border-red-500 @enderror"
                               placeholder="Ej: Cloro, Detergente, etc.">
                        @error('producto_insumo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Cantidad y Medidas --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Cantidad --}}
                    <div>
                        <label for="cantidad" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-cyan-600 mr-1"></i>
                            Cantidad <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               name="cantidad"
                               id="cantidad"
                               value="{{ old('cantidad', $insumo->cantidad) }}"
                               min="0"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('cantidad') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('cantidad')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unidad de Medida --}}
                    <div>
                        <label for="unidad_medida" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-ruler text-cyan-600 mr-1"></i>
                            Unidad de Medida <span class="text-red-500">*</span>
                        </label>
                        <select name="unidad_medida"
                                id="unidad_medida"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('unidad_medida') border-red-500 @enderror">
                            <option value="">Seleccione...</option>
                            <option value="kg" {{ old('unidad_medida', $insumo->unidad_medida) == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="g" {{ old('unidad_medida', $insumo->unidad_medida) == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                            <option value="L" {{ old('unidad_medida', $insumo->unidad_medida) == 'L' ? 'selected' : '' }}>Litros (L)</option>
                            <option value="ml" {{ old('unidad_medida', $insumo->unidad_medida) == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                        </select>
                        @error('unidad_medida')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Número de Lote --}}
                    <div>
                        <label for="numero_lote" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-barcode text-cyan-600 mr-1"></i>
                            Número de Lote
                        </label>
                        <input type="text"
                               name="numero_lote"
                               id="numero_lote"
                               value="{{ old('numero_lote', $insumo->numero_lote) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('numero_lote') border-red-500 @enderror"
                               placeholder="Ej: L-2025-001">
                        @error('numero_lote')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Vencimiento y Responsable --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Fecha de Vencimiento --}}
                    <div>
                        <label for="fecha_vencimiento" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-cyan-600 mr-1"></i>
                            Fecha de Vencimiento
                        </label>
                        <input type="date"
                               name="fecha_vencimiento"
                               id="fecha_vencimiento"
                               value="{{ old('fecha_vencimiento', $insumo->fecha_vencimiento ? $insumo->fecha_vencimiento->format('Y-m-d') : '') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha_vencimiento') border-red-500 @enderror">
                        @error('fecha_vencimiento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Responsable --}}
                    <div>
                        <label for="responsable" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-check text-cyan-600 mr-1"></i>
                            Responsable <span class="text-red-500">*</span>
                        </label>
                        <select name="responsable"
                                id="responsable"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('responsable') border-red-500 @enderror">
                            <option value="">Seleccione...</option>
                            @foreach($personal as $persona)
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $insumo->responsable) == $persona->nombre_completo ? 'selected' : '' }}>
                                    {{ $persona->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsable')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Proveedor --}}
                    <div>
                        <label for="proveedor" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-truck text-cyan-600 mr-1"></i>
                            Proveedor
                        </label>
                        <input type="text"
                               name="proveedor"
                               id="proveedor"
                               value="{{ old('proveedor', $insumo->proveedor) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('proveedor') border-red-500 @enderror"
                               placeholder="Nombre del proveedor">
                        @error('proveedor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('observaciones') border-red-500 @enderror"
                              placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones', $insumo->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.insumos.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i>
                            Restaurar
                        </button>

                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Insumo
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

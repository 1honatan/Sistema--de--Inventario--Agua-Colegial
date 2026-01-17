@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('page-title', 'Nuevo Producto')
@section('page-subtitle', 'Registrar un nuevo producto en el inventario')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('inventario.index') }}" class="text-cyan-600 hover:text-cyan-800">Inventario</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Nuevo Producto</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('inventario.productos.store') }}" method="POST" id="productoForm">
            @csrf

            <div class="space-y-6">
                {{-- Nombre del Producto --}}
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-cyan-600 mr-1"></i>
                        Nombre del Producto <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           value="{{ old('nombre') }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Botellón 20L, Agua Natural, etc.">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-cyan-600 mr-1"></i>
                        Descripción
                    </label>
                    <textarea name="descripcion"
                              id="descripcion"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('descripcion') border-red-500 @enderror"
                              placeholder="Descripción detallada del producto (opcional)">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
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
                        <option value="">Seleccione unidad...</option>
                        <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        <option value="litro" {{ old('unidad_medida') == 'litro' ? 'selected' : '' }}>Litro</option>
                        <option value="bolsa" {{ old('unidad_medida') == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                        <option value="bolsa de hielo" {{ old('unidad_medida') == 'bolsa de hielo' ? 'selected' : '' }}>Bolsa de Hielo</option>
                    </select>
                    @error('unidad_medida')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock Mínimo --}}
                <div>
                    <label for="stock_minimo" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle text-cyan-600 mr-1"></i>
                        Stock Mínimo
                    </label>
                    <input type="number"
                           name="stock_minimo"
                           id="stock_minimo"
                           value="{{ old('stock_minimo', 10) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('stock_minimo') border-red-500 @enderror"
                           placeholder="Cantidad mínima en stock">
                    @error('stock_minimo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Se generará alerta cuando el stock esté por debajo de este valor
                    </p>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('inventario.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
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
                            Guardar Producto
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

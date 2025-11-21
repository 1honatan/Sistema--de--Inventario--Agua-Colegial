@extends('layouts.app')

@section('title', 'Crear Producto')

@section('page-title', 'Crear Nuevo Producto')
@section('page-subtitle', 'Complete el formulario para registrar un nuevo producto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                <span class="mx-2 text-gray-500">/</span>
                <a href="{{ route('inventario.index') }}" class="text-blue-600 hover:text-blue-800">Inventario</a>
                <span class="mx-2 text-gray-500">/</span>
                <span class="text-gray-600">Crear Producto</span>
            </nav>
        </div>

        <form action="{{ route('admin.productos.store') }}" method="POST">
            @csrf

            {{-- Nombre --}}
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Producto <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                       placeholder="Ej: Botellón de 5 galones"
                       required
                       autofocus>
                @error('nombre')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                          placeholder="Descripción del producto (opcional)">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unidad de Medida --}}
            <div class="mb-6">
                <label for="unidad_medida" class="block text-sm font-medium text-gray-700 mb-2">
                    Unidad de Medida <span class="text-red-500">*</span>
                </label>
                <select id="unidad_medida"
                        name="unidad_medida"
                        class="select2-no-search @error('unidad_medida') border-red-500 @enderror"
                        required>
                    <option value="">Seleccione una unidad</option>
                    <option value="litro" {{ old('unidad_medida') == 'litro' ? 'selected' : '' }}>Litro</option>
                    <option value="bolsa" {{ old('unidad_medida') == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                </select>
                @error('unidad_medida')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="mb-6">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select id="estado"
                        name="estado"
                        class="select2-no-search @error('estado') border-red-500 @enderror"
                        required>
                    <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

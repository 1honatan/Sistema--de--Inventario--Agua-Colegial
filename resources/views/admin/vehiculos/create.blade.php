@extends('layouts.app')

@section('title', 'Agregar Vehículo')
@section('page-title', 'Agregar Nuevo Vehículo')
@section('page-subtitle', 'Registrar un nuevo vehículo a la flota')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('admin.vehiculos.index') }}" class="text-blue-600 hover:text-blue-800">Vehículos</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Agregar</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.vehiculos.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Placa --}}
                <div>
                    <label for="placa" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-blue-600 mr-1"></i>
                        Placa <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="placa"
                           id="placa"
                           value="{{ old('placa') }}"
                           required
                           maxlength="10"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('placa') border-red-500 @enderror"
                           placeholder="Ejemplo: ABC-123">
                    @error('placa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Responsable --}}
                <div>
                    <label for="responsable" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tie text-blue-600 mr-1"></i>
                        Responsable del Vehículo
                    </label>
                    <select name="responsable"
                            id="responsable"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('responsable') border-red-500 @enderror">
                        <option value="">Seleccione un responsable...</option>
                        @foreach($personal ?? [] as $persona)
                            <option value="{{ $persona->nombre_completo }}" {{ old('responsable') == $persona->nombre_completo ? 'selected' : '' }}>
                                {{ $persona->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsable')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Persona responsable del vehículo (opcional)
                    </p>
                </div>

                {{-- Marca --}}
                <div>
                    <label for="marca" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-blue-600 mr-1"></i>
                        Marca
                    </label>
                    <input type="text"
                           name="marca"
                           id="marca"
                           value="{{ old('marca') }}"
                           maxlength="100"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('marca') border-red-500 @enderror"
                           placeholder="Ejemplo: Toyota, Chevrolet, Nissan">
                    @error('marca')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Modelo --}}
                <div>
                    <label for="modelo" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-car text-blue-600 mr-1"></i>
                        Modelo
                    </label>
                    <input type="text"
                           name="modelo"
                           id="modelo"
                           value="{{ old('modelo') }}"
                           maxlength="100"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('modelo') border-red-500 @enderror"
                           placeholder="Ejemplo: Hilux 2023, NP300 2022">
                    @error('modelo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Capacidad --}}
                <div>
                    <label for="capacidad" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-weight text-blue-600 mr-1"></i>
                        Capacidad (unidades)
                    </label>
                    <input type="number"
                           name="capacidad"
                           id="capacidad"
                           value="{{ old('capacidad') }}"
                           min="1"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('capacidad') border-red-500 @enderror"
                           placeholder="Ejemplo: 500">
                    @error('capacidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Capacidad máxima de carga en unidades
                    </p>
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-flag text-blue-600 mr-1"></i>
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="estado"
                            id="estado"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('estado') border-red-500 @enderror">
                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observación --}}
                <div>
                    <label for="observacion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment text-blue-600 mr-1"></i>
                        Observación
                    </label>
                    <textarea name="observacion"
                              id="observacion"
                              rows="4"
                              maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('observacion') border-red-500 @enderror"
                              placeholder="Ingrese cualquier observación relevante...">{{ old('observacion') }}</textarea>
                    @error('observacion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.vehiculos.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i>
                            Limpiar
                        </button>

                        <button type="submit" class="btn btn-primary px-6 py-3">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Vehículo
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

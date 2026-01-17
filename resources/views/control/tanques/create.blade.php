@extends('layouts.app')

@section('title', 'Nueva Limpieza de Tanque')
@section('page-title', 'Nueva Limpieza de Tanque')
@section('page-subtitle', 'Complete el formulario para registrar la limpieza del tanque')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.tanques.index') }}" class="text-cyan-600 hover:text-cyan-800">Tanques</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Nueva Limpieza</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.tanques.store') }}" method="POST" id="tanqueForm">
            @csrf

            <div class="space-y-6">
                {{-- Información del Tanque --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fecha Limpieza --}}
                    <div>
                        <label for="fecha_limpieza" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                            Fecha de Limpieza <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="fecha_limpieza"
                               id="fecha_limpieza"
                               value="{{ old('fecha_limpieza', date('Y-m-d')) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha_limpieza') border-red-500 @enderror">
                        @error('fecha_limpieza')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de Tanque --}}
                    <div>
                        <label for="nombre_tanque" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-water text-cyan-600 mr-1"></i>
                            Tipo de Tanque <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nombre_tanque"
                               id="nombre_tanque"
                               value="{{ old('nombre_tanque') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('nombre_tanque') border-red-500 @enderror"
                               placeholder="Ej: Tanque Principal, Cisterna, etc.">
                        @error('nombre_tanque')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Capacidad y Responsable --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Capacidad --}}
                    <div>
                        <label for="capacidad_litros" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tint text-cyan-600 mr-1"></i>
                            Capacidad (Litros)
                        </label>
                        <input type="number"
                               step="0.01"
                               name="capacidad_litros"
                               id="capacidad_litros"
                               value="{{ old('capacidad_litros') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('capacidad_litros') border-red-500 @enderror"
                               placeholder="Ej: 5000">
                        @error('capacidad_litros')
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
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable') == $persona->nombre_completo ? 'selected' : '' }}>
                                    {{ $persona->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsable')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Supervisado por --}}
                    <div>
                        <label for="supervisado_por" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-tie text-cyan-600 mr-1"></i>
                            Supervisado por
                        </label>
                        <input type="text"
                               name="supervisado_por"
                               id="supervisado_por"
                               value="{{ old('supervisado_por') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('supervisado_por') border-red-500 @enderror"
                               placeholder="Nombre del supervisor">
                        @error('supervisado_por')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Próxima Limpieza --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="proxima_limpieza" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-check text-cyan-600 mr-1"></i>
                            Próxima Limpieza
                        </label>
                        <input type="date"
                               name="proxima_limpieza"
                               id="proxima_limpieza"
                               value="{{ old('proxima_limpieza') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('proxima_limpieza') border-red-500 @enderror">
                        @error('proxima_limpieza')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Procedimiento de Limpieza --}}
                <div>
                    <label for="procedimiento_limpieza" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-list-ol text-cyan-600 mr-1"></i>
                        Procedimiento de Limpieza
                    </label>
                    <textarea name="procedimiento_limpieza"
                              id="procedimiento_limpieza"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('procedimiento_limpieza') border-red-500 @enderror"
                              placeholder="Describa el procedimiento de limpieza realizado...">{{ old('procedimiento_limpieza') }}</textarea>
                    @error('procedimiento_limpieza')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Productos de Desinfección --}}
                <div>
                    <label for="productos_desinfeccion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-spray-can text-cyan-600 mr-1"></i>
                        Productos de Desinfección
                    </label>
                    <textarea name="productos_desinfeccion"
                              id="productos_desinfeccion"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('productos_desinfeccion') border-red-500 @enderror"
                              placeholder="Liste los productos utilizados para la desinfección...">{{ old('productos_desinfeccion') }}</textarea>
                    @error('productos_desinfeccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                              placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.tanques.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
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
                            Guardar Limpieza
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

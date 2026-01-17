@extends('layouts.app')

@section('title', 'Editar Limpieza Fosa Séptica')
@section('page-title', 'Editar Limpieza Fosa Séptica')
@section('page-subtitle', 'Modifique los datos del registro #' . $fosa->id)

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.fosa-septica.index') }}" class="text-cyan-600 hover:text-cyan-800">Fosa Séptica</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar Registro #{{ $fosa->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.fosa-septica.update', $fosa) }}" method="POST" id="fosaForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información de la Limpieza --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Fecha de Limpieza --}}
                    <div>
                        <label for="fecha_limpieza" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                            Fecha de Limpieza <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="fecha_limpieza"
                               id="fecha_limpieza"
                               value="{{ old('fecha_limpieza', $fosa->fecha_limpieza->format('Y-m-d')) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha_limpieza') border-red-500 @enderror">
                        @error('fecha_limpieza')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de Fosa --}}
                    <div>
                        <label for="tipo_fosa" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-cyan-600 mr-1"></i>
                            Tipo de Fosa <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="tipo_fosa"
                               id="tipo_fosa"
                               value="{{ old('tipo_fosa', $fosa->tipo_fosa) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('tipo_fosa') border-red-500 @enderror"
                               placeholder="Ej: Fosa Principal, Fosa #1">
                        @error('tipo_fosa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Próxima Limpieza --}}
                    <div>
                        <label for="proxima_limpieza" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-cyan-600 mr-1"></i>
                            Próxima Limpieza <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="proxima_limpieza"
                               id="proxima_limpieza"
                               value="{{ old('proxima_limpieza', $fosa->proxima_limpieza ? $fosa->proxima_limpieza->format('Y-m-d') : '') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('proxima_limpieza') border-red-500 @enderror">
                        @error('proxima_limpieza')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Responsable y Empresa --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <option value="">Seleccione responsable...</option>
                            @foreach($personal as $persona)
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $fosa->responsable) == $persona->nombre_completo ? 'selected' : '' }}>
                                    {{ $persona->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsable')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empresa Contratada --}}
                    <div>
                        <label for="empresa_contratada" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-building text-cyan-600 mr-1"></i>
                            Empresa Contratada <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="empresa_contratada"
                               id="empresa_contratada"
                               value="{{ old('empresa_contratada', $fosa->empresa_contratada) }}"
                               readonly
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        @error('empresa_contratada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Detalle del Trabajo --}}
                <div>
                    <label for="detalle_trabajo" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tasks text-cyan-600 mr-1"></i>
                        Tipo de Trabajo Realizado <span class="text-red-500">*</span>
                    </label>
                    <select name="detalle_trabajo"
                            id="detalle_trabajo"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('detalle_trabajo') border-red-500 @enderror">
                        <option value="">Seleccione el tipo de trabajo...</option>
                        <option value="Limpieza y Retiro" {{ old('detalle_trabajo', $fosa->detalle_trabajo) == 'Limpieza y Retiro' ? 'selected' : '' }}>
                            Limpieza y Retiro
                        </option>
                        <option value="Retiro de Residuos" {{ old('detalle_trabajo', $fosa->detalle_trabajo) == 'Retiro de Residuos' ? 'selected' : '' }}>
                            Retiro de Residuos
                        </option>
                    </select>
                    @error('detalle_trabajo')
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
                              placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones', $fosa->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.fosa-septica.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
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
                            Actualizar Registro
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

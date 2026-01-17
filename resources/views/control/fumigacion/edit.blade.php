@extends('layouts.app')

@section('title', 'Editar Fumigación')
@section('page-title', 'Editar Fumigación')
@section('page-subtitle', 'Modifique los datos del registro #' . $fumigacion->id)

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.fumigacion.index') }}" class="text-cyan-600 hover:text-cyan-800">Fumigación</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar Registro #{{ $fumigacion->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.fumigacion.update', $fumigacion) }}" method="POST" id="fumigacionForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información de la Fumigación --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fecha de Fumigación --}}
                    <div>
                        <label for="fecha_fumigacion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                            Fecha de Fumigación <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="fecha_fumigacion"
                               id="fecha_fumigacion"
                               value="{{ old('fecha_fumigacion', $fumigacion->fecha_fumigacion->format('Y-m-d')) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha_fumigacion') border-red-500 @enderror">
                        @error('fecha_fumigacion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Área Fumigada --}}
                    <div>
                        <label for="area_fumigada" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marked-alt text-cyan-600 mr-1"></i>
                            Área Fumigada <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="area_fumigada"
                               id="area_fumigada"
                               value="{{ old('area_fumigada', $fumigacion->area_fumigada) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('area_fumigada') border-red-500 @enderror"
                               placeholder="Ej: Área de producción, Bodega, etc.">
                        @error('area_fumigada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Producto y Cantidad --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Producto Utilizado --}}
                    <div>
                        <label for="producto_utilizado" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-prescription-bottle text-cyan-600 mr-1"></i>
                            Producto Utilizado <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="producto_utilizado"
                               id="producto_utilizado"
                               value="{{ old('producto_utilizado', $fumigacion->producto_utilizado) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('producto_utilizado') border-red-500 @enderror"
                               placeholder="Ej: Insecticida, Raticida, etc.">
                        @error('producto_utilizado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cantidad del Producto --}}
                    <div>
                        <label for="cantidad_producto" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-cyan-600 mr-1"></i>
                            Cantidad del Producto <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               name="cantidad_producto"
                               id="cantidad_producto"
                               value="{{ old('cantidad_producto', $fumigacion->cantidad_producto) }}"
                               min="0"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('cantidad_producto') border-red-500 @enderror">
                        @error('cantidad_producto')
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
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $fumigacion->responsable) == $persona->nombre_completo ? 'selected' : '' }}>
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
                            Empresa Contratada
                        </label>
                        <input type="text"
                               name="empresa_contratada"
                               id="empresa_contratada"
                               value="{{ old('empresa_contratada', $fumigacion->empresa_contratada) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('empresa_contratada') border-red-500 @enderror"
                               placeholder="Nombre de la empresa (opcional)">
                        @error('empresa_contratada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Próxima Fumigación --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="proxima_fumigacion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-cyan-600 mr-1"></i>
                            Próxima Fumigación
                        </label>
                        <input type="date"
                               name="proxima_fumigacion"
                               id="proxima_fumigacion"
                               value="{{ old('proxima_fumigacion', $fumigacion->proxima_fumigacion ? $fumigacion->proxima_fumigacion->format('Y-m-d') : '') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('proxima_fumigacion') border-red-500 @enderror">
                        @error('proxima_fumigacion')
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
                              placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones', $fumigacion->observaciones) }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.fumigacion.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
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

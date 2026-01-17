@extends('layouts.app')

@section('title', 'Editar Mantenimiento')
@section('page-title', 'Editar Mantenimiento')
@section('page-subtitle', 'Modifique los datos del registro de mantenimiento #' . $mantenimiento->id)

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.mantenimiento.index') }}" class="text-cyan-600 hover:text-cyan-800">Mantenimiento</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar Registro #{{ $mantenimiento->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.mantenimiento.update', $mantenimiento) }}" method="POST" id="mantenimientoForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información del Equipo --}}
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
                               value="{{ old('fecha', $mantenimiento->fecha->format('Y-m-d')) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha') border-red-500 @enderror">
                        @error('fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Próxima Fecha --}}
                    <div>
                        <label for="proxima_fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-check text-cyan-600 mr-1"></i>
                            Próxima Fecha de Mantenimiento
                        </label>
                        <input type="date"
                               name="proxima_fecha"
                               id="proxima_fecha"
                               value="{{ old('proxima_fecha', $mantenimiento->proxima_fecha ? $mantenimiento->proxima_fecha->format('Y-m-d') : '') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('proxima_fecha') border-red-500 @enderror">
                        @error('proxima_fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Equipos a Mantener --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-tools text-cyan-600 mr-1"></i>
                        Equipos a Mantener <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-gray-50 p-4 rounded-lg">
                        @php
                        $equipos = [
                            'Máquina de Agua Natural',
                            'Máquina de Limón y Sabor',
                            'Máquina de Bolos',
                            'Máquina de Hielo',
                            'Turiles Grandes (Gelatina y Bolos)',
                            'Turiles Medianos (Gelatina y Bolos)',
                            'Máquina Limpiadora de Botellones 20L',
                            'Otro'
                        ];
                        $equiposSeleccionados = old('equipo', $mantenimiento->equipo ?? []);
                        @endphp
                        @foreach($equipos as $eq)
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input type="checkbox"
                                       name="equipo[]"
                                       value="{{ $eq }}"
                                       {{ in_array($eq, $equiposSeleccionados) ? 'checked' : '' }}
                                       class="w-5 h-5 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                                <span class="text-gray-700">{{ $eq }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('equipo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Personal Responsable --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Realizado por --}}
                    <div>
                        <label for="id_personal" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-check text-cyan-600 mr-1"></i>
                            Realizado por <span class="text-red-500">*</span>
                        </label>
                        <select name="id_personal"
                                id="id_personal"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('id_personal') border-red-500 @enderror">
                            <option value="">Seleccione el personal...</option>
                            @foreach($personal as $p)
                                <option value="{{ $p->id }}" {{ old('id_personal', $mantenimiento->id_personal) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_personal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Supervisado por --}}
                    <div>
                        <label for="supervisado_por" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-tie text-cyan-600 mr-1"></i>
                            Supervisado por <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="supervisado_por"
                               id="supervisado_por"
                               value="{{ old('supervisado_por', $mantenimiento->supervisado_por ?? 'Lucia Cruz Farfan') }}"
                               readonly
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        @error('supervisado_por')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Productos de Limpieza --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-spray-can text-cyan-600 mr-1"></i>
                        Productos de Limpieza Utilizados <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-lg">
                        @php
                        $productosSeleccionados = old('productos_limpieza', $mantenimiento->productos_limpieza ?? []);
                        @endphp
                        @foreach($productosLimpieza as $producto)
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input type="checkbox"
                                       name="productos_limpieza[]"
                                       value="{{ $producto }}"
                                       {{ in_array($producto, $productosSeleccionados) ? 'checked' : '' }}
                                       class="w-5 h-5 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                                <span class="text-gray-700">{{ $producto }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('productos_limpieza')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.mantenimiento.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
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
                            Actualizar Mantenimiento
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

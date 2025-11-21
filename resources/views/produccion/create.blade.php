@extends('layouts.app')

@section('title', 'Registrar Producción Diaria')

@section('page-title', 'Registrar Producción Diaria')
@section('page-subtitle', 'Registra entrada de productos al inventario')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        {{-- Breadcrumb --}}
        <div class="mb-6">
            <nav class="text-sm">
                @if(auth()->user()->rol->nombre === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                @elseif(auth()->user()->rol->nombre === 'inventario')
                    <a href="{{ route('inventario.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                @elseif(auth()->user()->rol->nombre === 'produccion')
                    <a href="{{ route('produccion.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                @endif
                <span class="mx-2 text-gray-500">/</span>
                <a href="{{ route('produccion.index') }}" class="text-blue-600 hover:text-blue-800">Producción</a>
                <span class="mx-2 text-gray-500">/</span>
                <span class="text-gray-600">Registrar Producción Diaria</span>
            </nav>
        </div>

        {{-- Mensaje de Error --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Mensaje de Éxito --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('produccion.store') }}" method="POST">
            @csrf

            {{-- Producto --}}
            <div class="mb-6">
                <label for="id_producto" class="block text-sm font-medium text-gray-700 mb-2">
                    Producto <span class="text-red-500">*</span>
                </label>
                <select id="id_producto"
                        name="id_producto"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_producto') border-red-500 @enderror"
                        required
                        autofocus>
                    <option value="">Seleccione un producto</option>
                    @foreach($productos ?? [] as $producto)
                        <option value="{{ $producto->id }}" {{ old('id_producto') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }} - Stock: {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
                        </option>
                    @endforeach
                </select>
                @error('id_producto')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipo de Movimiento (Solo Entrada - Oculto) --}}
            <input type="hidden" name="tipo_movimiento" value="entrada">

            {{-- Indicador Visual de Entrada --}}
            <div class="mb-6">
                <div class="p-4 border-2 border-green-300 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                        <div>
                            <span class="block text-sm font-semibold text-green-900">Entrada de Producción</span>
                            <span class="block text-xs text-green-700">Se agregará al stock disponible</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cantidad --}}
            <div class="mb-6">
                <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-2">
                    Cantidad Producida <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="cantidad"
                       name="cantidad"
                       value="{{ old('cantidad') }}"
                       min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cantidad') border-red-500 @enderror"
                       placeholder="Ingrese la cantidad producida"
                       required>
                @error('cantidad')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Ingrese la cantidad de unidades producidas
                </p>
            </div>

            {{-- Usuario Responsable --}}
            <div class="mb-6">
                <label for="id_usuario" class="block text-sm font-medium text-gray-700 mb-2">
                    Usuario Responsable <span class="text-red-500">*</span>
                </label>
                <select id="id_usuario"
                        name="id_usuario"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_usuario') border-red-500 @enderror"
                        required>
                    <option value="">Seleccione un usuario</option>
                    @foreach($usuarios ?? [] as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('id_usuario', auth()->id()) == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} - {{ $usuario->personal->nombre_completo ?? $usuario->email }}
                        </option>
                    @endforeach
                </select>
                @error('id_usuario')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Seleccione el usuario responsable de la producción
                </p>
            </div>

            {{-- Campos de Trazabilidad --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Información de Trazabilidad <span class="text-gray-400 font-normal">(Opcional)</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Origen --}}
                    <div>
                        <label for="origen" class="block text-sm font-medium text-gray-700 mb-2">
                            Origen / Línea de Producción
                        </label>
                        <input type="text"
                               id="origen"
                               name="origen"
                               value="{{ old('origen') }}"
                               maxlength="200"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('origen') border-red-500 @enderror"
                               placeholder="Ej: Línea de Producción 1, Planta Principal">
                        @error('origen')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Destino --}}
                    <div>
                        <label for="destino" class="block text-sm font-medium text-gray-700 mb-2">
                            Destino / Almacén
                        </label>
                        <input type="text"
                               id="destino"
                               name="destino"
                               value="{{ old('destino', 'Almacén General') }}"
                               maxlength="200"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('destino') border-red-500 @enderror"
                               placeholder="Ej: Almacén General, Bodega Principal">
                        @error('destino')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Fecha del Movimiento --}}
            <div class="mb-6">
                <label for="fecha_movimiento" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Producción <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       id="fecha_movimiento"
                       name="fecha_movimiento"
                       value="{{ old('fecha_movimiento', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_movimiento') border-red-500 @enderror"
                       required>
                @error('fecha_movimiento')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    La fecha no puede ser futura
                </p>
            </div>

            {{-- Observación --}}
            <div class="mb-6">
                <label for="observacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Observación <span class="text-gray-400">(Opcional)</span>
                </label>
                <textarea id="observacion"
                          name="observacion"
                          rows="3"
                          maxlength="500"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('observacion') border-red-500 @enderror"
                          placeholder="Agregue notas o comentarios sobre esta producción (opcional)">{{ old('observacion') }}</textarea>
                @error('observacion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    Máximo 500 caracteres
                </p>
            </div>

            {{-- Información Adicional --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Importante</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>Entrada Automática:</strong> Esta producción se registrará como entrada en el inventario</li>
                                <li>El stock del producto se incrementará automáticamente</li>
                                <li>Se generará un registro de movimiento con toda la información ingresada</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('produccion.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Registrar Producción
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

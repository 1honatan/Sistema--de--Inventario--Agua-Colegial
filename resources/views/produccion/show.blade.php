@extends('layouts.app')

@section('title', 'Detalle de Producción')
@section('page-title', 'Detalle de Producción')
@section('page-subtitle', 'Información completa del lote de producción')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Lote: {{ $produccion->lote }}</h2>
            <p class="text-sm text-gray-600 mt-1">Registrado el {{ $produccion->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <a href="{{ route('produccion.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
    </div>

    <!-- Main Details Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-info-circle mr-2"></i>
                Información General
            </h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Código de Lote -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Código de Lote</label>
                    <p class="text-lg font-mono font-bold text-blue-600">{{ $produccion->lote }}</p>
                </div>

                <!-- Fecha de Producción -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Producción</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar text-gray-400 mr-2"></i>
                        {{ $produccion->fecha_produccion->format('d/m/Y') }}
                    </p>
                </div>

                <!-- Producto -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Producto</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-box text-gray-400 mr-2"></i>
                        {{ $produccion->producto->nombre }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        Tipo: {{ $produccion->producto->tipoProducto->nombre ?? 'N/A' }}
                    </p>
                </div>

                <!-- Cantidad Producida -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Cantidad Producida</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold">
                            {{ number_format($produccion->cantidad) }} unidades
                        </span>
                    </p>
                </div>

                <!-- Responsable -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Responsable</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user text-gray-400 mr-2"></i>
                        {{ $produccion->personal->nombre_completo ?? 'N/A' }}
                    </p>
                    @if($produccion->personal)
                        <p class="text-sm text-gray-600 mt-1">
                            Cargo: {{ $produccion->personal->cargo }} - {{ $produccion->personal->area }}
                        </p>
                    @endif
                </div>

                <!-- Fecha de Registro -->
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Registro</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                        {{ $produccion->created_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-2xl mr-3 mt-1"></i>
            <div>
                <h4 class="text-lg font-semibold text-blue-900 mb-2">Información Adicional</h4>
                <p class="text-sm text-blue-800">
                    Este lote fue registrado en el inventario automáticamente al momento de su creación.
                    Puede consultar el movimiento de inventario correspondiente en el módulo de inventario.
                </p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('produccion.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Listado
        </a>
    </div>
</div>
@endsection

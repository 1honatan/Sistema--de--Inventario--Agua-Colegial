@extends('layouts.app')

@section('title', 'Alertas de Stock Bajo')

@section('page-title', 'Alertas de Stock')
@section('page-subtitle', 'Gestión de alertas de inventario bajo')

@section('content')
<div class="container mx-auto px-4">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            @if(auth()->user()->rol->nombre === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            @elseif(auth()->user()->rol->nombre === 'inventario')
                <a href="{{ route('inventario.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            @endif
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('inventario.index') }}" class="text-blue-600 hover:text-blue-800">Inventario</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Alertas de Stock</span>
        </nav>
    </div>

    {{-- Mensajes de éxito --}}
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

    {{-- Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('inventario.alertas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Estado de Alerta --}}
            <div>
                <label for="estado_alerta" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado de Alerta
                </label>
                <select id="estado_alerta" name="estado_alerta" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    <option value="activa" {{ request('estado_alerta') == 'activa' ? 'selected' : '' }}>Activas</option>
                    <option value="atendida" {{ request('estado_alerta') == 'atendida' ? 'selected' : '' }}>Atendidas</option>
                    <option value="ignorada" {{ request('estado_alerta') == 'ignorada' ? 'selected' : '' }}>Ignoradas</option>
                </select>
            </div>

            {{-- Nivel de Urgencia --}}
            <div>
                <label for="nivel_urgencia" class="block text-sm font-medium text-gray-700 mb-2">
                    Nivel de Urgencia
                </label>
                <select id="nivel_urgencia" name="nivel_urgencia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="critica" {{ request('nivel_urgencia') == 'critica' ? 'selected' : '' }}>Crítica</option>
                    <option value="alta" {{ request('nivel_urgencia') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="media" {{ request('nivel_urgencia') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="baja" {{ request('nivel_urgencia') == 'baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>

            {{-- Botón de Filtrar --}}
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    {{-- Resumen de Alertas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-red-600 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Críticas</p>
                    <p class="text-3xl font-bold">{{ $alertasCriticas ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-circle-exclamation text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-orange-500 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Alta</p>
                    <p class="text-3xl font-bold">{{ $alertasAltas ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-yellow-500 text-gray-900 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Media</p>
                    <p class="text-3xl font-bold">{{ \App\Models\AlertaStock::activas()->porNivelUrgencia('media')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-exclamation text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-blue-500 text-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Baja</p>
                    <p class="text-3xl font-bold">{{ \App\Models\AlertaStock::activas()->porNivelUrgencia('baja')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fa-solid fa-info-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de Alertas --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        @if($alertas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($alertas as $alerta)
                    @php
                        $colorBorder = match($alerta->nivel_urgencia) {
                            'critica' => 'border-red-600',
                            'alta' => 'border-orange-500',
                            'media' => 'border-yellow-500',
                            'baja' => 'border-blue-500',
                            default => 'border-gray-400'
                        };

                        $colorBg = match($alerta->nivel_urgencia) {
                            'critica' => 'bg-red-50',
                            'alta' => 'bg-orange-50',
                            'media' => 'bg-yellow-50',
                            'baja' => 'bg-blue-50',
                            default => 'bg-gray-50'
                        };

                        $stockActual = $alerta->cantidad_actual ?? 0;
                        $stockMinimo = $alerta->cantidad_minima ?? 10;
                        $porcentaje = $stockMinimo > 0 ? min(($stockActual / $stockMinimo) * 100, 100) : 0;
                    @endphp

                    <div class="border-l-4 {{ $colorBorder }} {{ $colorBg }} rounded-lg p-5 hover:shadow-xl transition-all duration-300">
                        {{-- Header de la Card --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-12 h-12 rounded-full bg-white shadow-md flex items-center justify-center">
                                    <i class="fas fa-box text-2xl {{ $alerta->nivel_urgencia === 'critica' ? 'text-red-600' : ($alerta->nivel_urgencia === 'alta' ? 'text-orange-600' : 'text-yellow-600') }}"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $alerta->producto->nombre }}</h3>
                                    @if($alerta->producto->tipoProducto)
                                        <p class="text-sm text-gray-600">{{ $alerta->producto->tipoProducto->nombre }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Badge de urgencia y estado --}}
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $alerta->colorNivelUrgencia() }}">
                                <i class="{{ $alerta->iconoNivelUrgencia() }} mr-1"></i>
                                {{ ucfirst($alerta->nivel_urgencia) }}
                            </span>

                            @if($alerta->estado_alerta === 'activa')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fa-solid fa-bell mr-1"></i>
                                    Activa
                                </span>
                            @elseif($alerta->estado_alerta === 'atendida')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Atendida
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    <i class="fa-solid fa-times-circle mr-1"></i>
                                    Ignorada
                                </span>
                            @endif
                        </div>

                        {{-- Información de stock --}}
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Stock Actual:</span>
                                <span class="text-3xl font-black {{ $alerta->cantidad_actual <= 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ number_format($stockActual) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Stock Mínimo:</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($stockMinimo) }}</span>
                            </div>

                            {{-- Barra de progreso --}}
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full transition-all duration-500 {{ $alerta->nivel_urgencia === 'critica' ? 'bg-red-600' : ($alerta->nivel_urgencia === 'alta' ? 'bg-orange-500' : 'bg-yellow-500') }}"
                                         style="width: {{ $porcentaje }}%;"></div>
                                </div>
                                <p class="text-xs text-center text-gray-600 mt-2">
                                    {{ round($porcentaje) }}% del stock mínimo
                                </p>
                            </div>
                        </div>

                        {{-- Fecha de alerta --}}
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-200">
                            <i class="far fa-clock"></i>
                            <span>Alerta generada: {{ $alerta->fecha_alerta->format('d/m/Y H:i') }}</span>
                        </div>

                        {{-- Acciones --}}
                        @if($alerta->estado_alerta === 'activa')
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('inventario.alertas.atender', $alerta) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                                        <i class="fa-solid fa-check mr-2"></i>
                                        Atender
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('inventario.alertas.ignorar', $alerta) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                                        <i class="fa-solid fa-times mr-2"></i>
                                        Ignorar
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-2">
                                <span class="text-sm text-gray-400 italic">Sin acciones disponibles</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                {{ $alertas->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-50 rounded-full mb-4">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay alertas</h3>
                <p class="text-gray-600">
                    No se encontraron alertas con los filtros seleccionados.
                </p>
            </div>
        @endif
    </div>

    {{-- Información Adicional --}}
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">Información sobre alertas</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Crítica:</strong> Stock agotado (0 unidades)</li>
                        <li><strong>Alta:</strong> Stock menor al 25% del mínimo</li>
                        <li><strong>Media:</strong> Stock entre 25% y 50% del mínimo</li>
                        <li><strong>Baja:</strong> Stock entre 50% y 100% del mínimo</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

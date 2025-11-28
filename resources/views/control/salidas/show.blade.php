@extends('layouts.app')

@section('title', 'Detalle de Salida')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-to-r from-blue-900 to-blue-800 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold">
                    <i class="fas fa-file-alt mr-2"></i>
                    Registro #{{ $salida->id }}
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('control.salidas.edit', $salida) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('control.salidas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Información General -->
            <div class="border rounded p-4 mb-4 bg-gray-50">
                <h4 class="font-bold text-lg mb-3 text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información General
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-clipboard-list mr-1"></i>
                            Tipo de Salida
                        </p>
                        <p class="font-bold text-lg">
                            <span class="badge badge-primary" style="font-size: 0.9rem;">{{ $salida->tipo_salida ?? 'Sin Tipo' }}</span>
                        </p>
                    </div>

                    @if($salida->tipo_salida === 'Pedido Cliente')
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1"></i>
                                Cliente
                            </p>
                            <p class="font-bold text-lg">{{ $salida->nombre_cliente ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                Dirección
                            </p>
                            <p class="font-bold text-lg">{{ $salida->direccion_entrega ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-1"></i>
                                Teléfono
                            </p>
                            <p class="font-bold text-lg">{{ $salida->telefono_cliente ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1"></i>
                                Chofer
                            </p>
                            <p class="font-bold text-lg">{{ $salida->chofer ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user-tie mr-1"></i>
                                Distribuidor
                            </p>
                            <p class="font-bold text-lg">{{ $salida->nombre_distribuidor ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-car mr-1"></i>
                                Vehículo
                            </p>
                            <p class="font-bold text-lg">{{ $salida->vehiculo_placa ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Fecha
                            </p>
                            <p class="font-bold text-lg">{{ $salida->fecha->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                Hora de Llegada
                            </p>
                            <p class="font-bold text-lg">{{ $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : '-' }}</p>
                        </div>
                    @else
                    @if($salida->chofer && $salida->nombre_distribuidor && $salida->chofer != $salida->nombre_distribuidor)
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i>
                            Chofer
                        </p>
                        <p class="font-bold text-lg">{{ $salida->chofer }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user-tie mr-1"></i>
                            Distribuidor
                        </p>
                        <p class="font-bold text-lg">{{ $salida->nombre_distribuidor }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user-tie mr-1"></i>
                            Responsable / Distribuidor
                        </p>
                        <p class="font-bold text-lg">{{ $salida->nombre_distribuidor }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-car mr-1"></i>
                            Vehículo
                        </p>
                        <p class="font-bold text-lg">{{ $salida->vehiculo_placa ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Fecha
                        </p>
                        <p class="font-bold text-lg">{{ $salida->fecha->format('d/m/Y') }}</p>
                    </div>
                    @if($salida->tipo_salida !== 'Pedido Cliente')
                    <div>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Hora de Llegada
                        </p>
                        <p class="font-bold text-lg">{{ $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : '-' }}</p>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Productos -->
            <div class="border rounded p-4 mb-4 bg-green-50">
                <h4 class="font-bold text-lg mb-3 text-green-900">
                    <i class="fas fa-boxes mr-2"></i>
                    Productos Despachados
                </h4>

                @php
                    $productos = [
                        ['nombre' => 'Botellones', 'cantidad' => $salida->botellones ?? 0, 'icono' => 'fa-water', 'color' => 'green'],
                        ['nombre' => 'Retornos', 'cantidad' => $salida->retornos ?? 0, 'icono' => 'fa-undo', 'color' => 'yellow'],
                        ['nombre' => 'Bolo Grande', 'cantidad' => $salida->bolo_grande ?? 0, 'icono' => 'fa-shopping-bag', 'color' => 'green'],
                        ['nombre' => 'Bolo Pequeño', 'cantidad' => $salida->bolo_pequeño ?? 0, 'icono' => 'fa-shopping-bag', 'color' => 'green'],
                        ['nombre' => 'Gelatina', 'cantidad' => $salida->gelatina ?? 0, 'icono' => 'fa-cube', 'color' => 'green'],
                        ['nombre' => 'Agua Saborizada', 'cantidad' => $salida->agua_saborizada ?? 0, 'icono' => 'fa-tint', 'color' => 'green'],
                        ['nombre' => 'Agua Limón', 'cantidad' => $salida->agua_limon ?? 0, 'icono' => 'fa-lemon', 'color' => 'green'],
                        ['nombre' => 'Agua Natural', 'cantidad' => $salida->agua_natural ?? 0, 'icono' => 'fa-water', 'color' => 'green'],
                        ['nombre' => 'Hielo', 'cantidad' => $salida->hielo ?? 0, 'icono' => 'fa-snowflake', 'color' => 'green'],
                        ['nombre' => 'Dispenser', 'cantidad' => $salida->dispenser ?? 0, 'icono' => 'fa-faucet', 'color' => 'green'],
                        ['nombre' => 'Choreados', 'cantidad' => $salida->choreados ?? 0, 'icono' => 'fa-tint-slash', 'color' => 'red'],
                    ];

                    $productosConCantidad = collect($productos)->filter(fn($p) => $p['cantidad'] > 0);
                    $totalDespachado = collect($productos)->whereIn('color', ['green'])->sum('cantidad');
                @endphp

                @if($productosConCantidad->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($productosConCantidad as $producto)
                        @php
                            $borderColor = match($producto['color']) {
                                'yellow' => 'border-yellow-500',
                                'red' => 'border-red-500',
                                'orange' => 'border-orange-500',
                                default => 'border-green-500'
                            };
                            $textColor = match($producto['color']) {
                                'yellow' => 'text-yellow-600',
                                'red' => 'text-red-600',
                                'orange' => 'text-orange-600',
                                default => 'text-green-600'
                            };
                            $bgColor = match($producto['color']) {
                                'yellow' => 'bg-yellow-50',
                                'red' => 'bg-red-50',
                                'orange' => 'bg-orange-50',
                                default => 'bg-white'
                            };
                        @endphp
                        <div class="text-center p-4 {{ $bgColor }} rounded-lg shadow-sm border-l-4 {{ $borderColor }} hover:shadow-md transition">
                            <i class="fas {{ $producto['icono'] }} text-3xl mb-2 {{ $textColor }}"></i>
                            <p class="text-xs font-semibold text-gray-600 uppercase">{{ $producto['nombre'] }}</p>
                            <p class="text-3xl font-bold {{ $textColor }}">{{ number_format($producto['cantidad']) }}</p>
                            <p class="text-xs text-gray-500">unidades</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg text-center shadow-lg">
                        <p class="text-sm text-white font-semibold uppercase tracking-wide mb-1">
                            <i class="fas fa-calculator mr-2"></i>Total de Productos Despachados
                        </p>
                        <p class="text-5xl font-bold text-white">{{ number_format($totalDespachado) }}</p>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-5xl mb-3"></i>
                        <p class="font-semibold">No se despacharon productos en este registro</p>
                    </div>
                @endif
            </div>

            <!-- Detalle de Retornos -->
            @if($salida->tipo_salida !== 'Pedido Cliente')
            @php
                $retornosDetalle = [
                    ['nombre' => 'Botellones', 'cantidad' => $salida->retorno_botellones ?? 0, 'icono' => 'fa-water'],
                    ['nombre' => 'Bolo Grande', 'cantidad' => $salida->retorno_bolo_grande ?? 0, 'icono' => 'fa-shopping-bag'],
                    ['nombre' => 'Bolo Pequeño', 'cantidad' => $salida->retorno_bolo_pequeno ?? 0, 'icono' => 'fa-shopping-bag'],
                    ['nombre' => 'Gelatina', 'cantidad' => $salida->retorno_gelatina ?? 0, 'icono' => 'fa-cube'],
                    ['nombre' => 'Agua Saborizada', 'cantidad' => $salida->retorno_agua_saborizada ?? 0, 'icono' => 'fa-tint'],
                    ['nombre' => 'Agua Limón', 'cantidad' => $salida->retorno_agua_limon ?? 0, 'icono' => 'fa-lemon'],
                    ['nombre' => 'Agua Natural', 'cantidad' => $salida->retorno_agua_natural ?? 0, 'icono' => 'fa-water'],
                    ['nombre' => 'Hielo', 'cantidad' => $salida->retorno_hielo ?? 0, 'icono' => 'fa-snowflake'],
                    ['nombre' => 'Dispenser', 'cantidad' => $salida->retorno_dispenser ?? 0, 'icono' => 'fa-faucet'],
                ];
                $totalRetornos = collect($retornosDetalle)->sum('cantidad');
            @endphp

            <div class="border rounded p-4 mb-4 bg-yellow-50">
                <h4 class="font-bold text-lg mb-3 text-yellow-900">
                    <i class="fas fa-undo mr-2"></i>
                    Detalle de Productos Retornados
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($retornosDetalle as $retorno)
                    <div class="text-center p-4 bg-white rounded-lg shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition">
                        <i class="fas {{ $retorno['icono'] }} text-3xl mb-2 text-yellow-600"></i>
                        <p class="text-xs font-semibold text-gray-600 uppercase">{{ $retorno['nombre'] }}</p>
                        <p class="text-3xl font-bold text-yellow-700">{{ number_format($retorno['cantidad']) }}</p>
                        <p class="text-xs text-gray-500">unidades</p>
                    </div>
                    @endforeach
                </div>

                <!-- Total Retornos -->
                <div class="mt-4 p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-center shadow-lg">
                    <p class="text-sm text-white font-semibold uppercase tracking-wide mb-1">
                        <i class="fas fa-calculator mr-2"></i>Total de Productos Retornados
                    </p>
                    <p class="text-5xl font-bold text-white">{{ number_format($totalRetornos) }}</p>
                </div>
            </div>
            @endif

            <!-- Observaciones -->
            @if($salida->observaciones)
            <div class="border rounded p-4 bg-yellow-50">
                <h4 class="font-bold text-lg mb-3 text-yellow-900">
                    <i class="fas fa-sticky-note mr-2"></i>
                    Observaciones
                </h4>
                <p class="text-gray-700">{{ $salida->observaciones }}</p>
            </div>
            @endif

            <!-- Metadata -->
            <div class="mt-4 text-sm text-gray-500 text-right">
                <p>Creado: {{ $salida->created_at ? $salida->created_at->format('d/m/Y H:i') : 'No disponible' }}</p>
                <p>Última actualización: {{ $salida->updated_at ? $salida->updated_at->format('d/m/Y H:i') : 'No disponible' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

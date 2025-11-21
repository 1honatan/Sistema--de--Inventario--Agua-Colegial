@extends('layouts.app')

@section('title', 'Dashboard Producción')
@section('page-title', 'Panel de Producción')
@section('page-subtitle', 'Control y seguimiento de producción')

@push('styles')
<style>
    /* Animaciones */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-pulse-slow {
        animation: pulse 3s ease-in-out infinite;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    /* Tarjetas KPI mejoradas */
    .kpi-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #059669, #10b981, #34d399);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .kpi-card:hover::before {
        transform: translateX(0);
    }

    .kpi-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 40px rgba(5, 150, 105, 0.25);
        border-color: #10b981;
    }

    .kpi-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .kpi-card:hover .kpi-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Gradientes */
    .bg-gradient-green {
        background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
    }

    .bg-gradient-emerald {
        background: linear-gradient(135deg, #047857 0%, #059669 50%, #10b981 100%);
    }

    .bg-gradient-teal {
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);
    }

    /* Botones de acción */
    .action-btn {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        padding: 1.5rem;
        border-radius: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
        background: linear-gradient(135deg, #047857, #059669);
    }

    /* Tabla moderna */
    .modern-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .modern-table thead {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, #f0fdf4, #dcfce7);
        transform: scale(1.01);
    }

    /* Badge */
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Card section */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    /* Progress bar */
    .progress-bar-modern {
        height: 10px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill-modern {
        height: 100%;
        background: linear-gradient(90deg, #059669, #10b981);
        border-radius: 10px;
        transition: width 1s ease;
        position: relative;
        overflow: hidden;
    }

    .progress-fill-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Banner de Bienvenida -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-500 to-teal-500 rounded-3xl p-8 text-white shadow-2xl animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Panel de Producción 🏭</h2>
                <p class="text-green-100 text-lg">Bienvenido, {{ auth()->user()->nombre }}</p>
            </div>
            <div class="hidden md:block animate-float">
                <i class="fas fa-industry text-white text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Producción Hoy -->
        <div class="kpi-card animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Producción Hoy</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($produccionHoy ?? 0) }}</p>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-box text-green-500"></i>
                        unidades producidas
                    </p>
                </div>
                <div class="kpi-icon bg-gradient-green shadow-lg">
                    <i class="fas fa-industry text-white"></i>
                </div>
            </div>
        </div>

        <!-- Producción Semana -->
        <div class="kpi-card animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Producción Semanal</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($produccionSemana ?? 0) }}</p>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-calendar-week text-emerald-500"></i>
                        total de la semana
                    </p>
                </div>
                <div class="kpi-icon bg-gradient-emerald shadow-lg">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
        </div>

        <!-- Producción Mes -->
        <div class="kpi-card animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Producción Mensual</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($produccionMes ?? 0) }}</p>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-calendar-alt text-teal-500"></i>
                        total del mes
                    </p>
                </div>
                <div class="kpi-icon bg-gradient-teal shadow-lg">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Producción Semanal y Productos Más Producidos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Gráfico de producción por día -->
        <div class="section-card animate-fade-in-up" style="animation-delay: 0.5s;">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-green rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                Producción Diaria (Semana Actual)
            </h3>

            <div class="space-y-4">
                @php
                    $maxProduccion = max(array_column($produccionPorDia, 'cantidad'));
                @endphp
                @foreach($produccionPorDia as $dia)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3 w-32">
                                <span class="text-sm font-bold text-gray-800">{{ $dia['dia'] }}</span>
                                <span class="text-xs text-gray-500">{{ $dia['fecha'] }}</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ number_format($dia['cantidad']) }}</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern" style="width: {{ $maxProduccion > 0 ? ($dia['cantidad'] / $maxProduccion * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Últimas Producciones y Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Últimas Producciones -->
        <div class="lg:col-span-2 section-card animate-fade-in-up" style="animation-delay: 0.7s;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-green rounded-xl flex items-center justify-center">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    Últimas Producciones
                </h3>
                <a href="{{ route('produccion.index') }}" class="text-green-600 hover:text-green-700 font-semibold text-sm flex items-center gap-2 transition-all hover:gap-3">
                    Ver todas
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="modern-table">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasProducciones as $produccion)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $produccion->fecha_produccion ? \Carbon\Carbon::parse($produccion->fecha_produccion)->format('d/m/Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-green-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-800">{{ $produccion->producto->nombre ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="badge-modern bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i>
                                        {{ number_format($produccion->cantidad ?? 0) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <i class="fas fa-inbox text-5xl"></i>
                                        <p class="text-lg font-semibold">No hay producciones registradas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock de Productos -->
        <div class="section-card animate-fade-in-up" style="animation-delay: 0.8s;">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-warehouse text-white"></i>
                </div>
                Stock Actual
            </h3>

            <div class="space-y-3">
                @forelse($stockProductos as $producto)
                    <div class="bg-gradient-to-r from-gray-50 to-white border-2 {{ $producto->stock_actual < 10 ? 'border-red-300' : 'border-gray-200' }} rounded-2xl p-4 transition-all hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">{{ $producto->nombre }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $producto->unidad_medida ?? 'unidades' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold {{ $producto->stock_actual < 10 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($producto->stock_actual) }}
                                </p>
                                @if($producto->stock_actual < 10)
                                    <span class="text-xs text-red-600 font-semibold">
                                        <i class="fas fa-exclamation-triangle"></i> Bajo
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <i class="fas fa-box-open text-5xl"></i>
                            <p class="text-sm font-semibold">No hay productos</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('inventario.index') }}" class="mt-6 block w-full bg-gradient-green text-white text-center py-3 rounded-xl font-bold transition-all hover:shadow-xl hover:-translate-y-1">
                <i class="fas fa-eye mr-2"></i>
                Ver Inventario Completo
            </a>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="section-card animate-fade-in-up" style="animation-delay: 0.9s;">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white"></i>
            </div>
            Acciones Rápidas
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <a href="{{ route('produccion.create') }}" class="action-btn">
                <div class="relative z-10 flex flex-col items-center justify-center gap-3">
                    <i class="fas fa-plus-circle text-4xl"></i>
                    <span class="font-bold text-sm text-center">Registrar Producción</span>
                </div>
            </a>

            <a href="{{ route('produccion.index') }}" class="action-btn">
                <div class="relative z-10 flex flex-col items-center justify-center gap-3">
                    <i class="fas fa-list text-4xl"></i>
                    <span class="font-bold text-sm text-center">Ver Producciones</span>
                </div>
            </a>

            <a href="{{ route('inventario.index') }}" class="action-btn">
                <div class="relative z-10 flex flex-col items-center justify-center gap-3">
                    <i class="fas fa-warehouse text-4xl"></i>
                    <span class="font-bold text-sm text-center">Consultar Inventario</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Contador animado
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const kpiCards = document.querySelectorAll('.kpi-card p.text-4xl');
        kpiCards.forEach(card => {
            const finalValue = parseInt(card.textContent.replace(/,/g, ''));
            if (!isNaN(finalValue)) {
                card.textContent = '0';
                animateValue(card, 0, finalValue, 1500);
            }
        });
    });
</script>
@endpush

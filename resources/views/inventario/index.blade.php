@extends('layouts.app')

@section('title', 'Inventario')
@section('page-title', 'Inventario General')
@section('page-subtitle', 'Control de stock en tiempo real')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
        min-height: 100vh;
        position: relative;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    .stock-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 1.1rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .stock-badge-sin-stock {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        color: #374151;
    }

    .stock-badge-alto {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .stock-badge-medio {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .stock-badge-bajo {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    /* Botones de Acción Mejorados */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-action-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-action-edit:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: white;
    }

    .btn-action-history {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-action-history:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-action-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-action-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
    }

    .btn-action i {
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="modern-card-title mb-0">
                                <i class="fas fa-warehouse mr-2"></i>
                                Stock de Productos
                            </h3>
                            <p class="modern-card-subtitle mb-0">
                                Actualizado en tiempo real
                            </p>
                        </div>
                        @if(auth()->user()->rol->nombre === 'inventario' || auth()->user()->rol->nombre === 'admin')
                            <a href="{{ route('admin.productos.create') }}" class="btn-modern btn-success">
                                <i class="fas fa-plus-circle"></i>
                                Crear Producto
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock por Producto -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="modern-card-body">
                    <div class="section-header mb-4">
                        <i class="fas fa-boxes text-primary" style="font-size: 1.8rem; margin-right: 12px;"></i>
                        <h4 style="font-weight: 800; font-size: 1.4rem; background: linear-gradient(135deg, #1e40af 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Stock por Producto
                        </h4>
                    </div>

                    <div class="table-responsive">
                        <table class="modern-table" id="inventarioTable">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th>Unidad</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasProducts = false;
                                @endphp
                                @foreach($productos ?? [] as $producto)
                                    @php
                                        $stock = \App\Models\Inventario::stockDisponible($producto->id);
                                        $hasProducts = true;

                                        // Determinar nivel de stock
                                        if ($stock <= 0) {
                                            $nivel = 'sin-stock';
                                            $colorBadge = 'sin-stock';
                                            $estadoLabel = 'Sin Stock';
                                            $estadoIcon = 'fa-box-open';
                                        } elseif ($stock > 100) {
                                            $nivel = 'alto';
                                            $colorBadge = 'alto';
                                            $estadoLabel = 'Normal';
                                            $estadoIcon = 'fa-check-circle';
                                        } elseif ($stock > 50) {
                                            $nivel = 'medio';
                                            $colorBadge = 'medio';
                                            $estadoLabel = 'Advertencia';
                                            $estadoIcon = 'fa-exclamation-circle';
                                        } else {
                                            $nivel = 'bajo';
                                            $colorBadge = 'bajo';
                                            $estadoLabel = 'Crítico';
                                            $estadoIcon = 'fa-times-circle';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-box text-primary mr-3" style="font-size: 1.5rem;"></i>
                                                <div>
                                                    <div style="font-weight: 700; font-size: 1rem; color: #1f2937;">
                                                        {{ $producto->nombre }}
                                                    </div>
                                                    <div style="font-size: 0.85rem; color: #6b7280;">
                                                        {{ $producto->descripcion ?? 'Sin descripción' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-size: 0.9rem; color: #6b7280; font-weight: 600;">
                                                <i class="fas fa-tag text-sm"></i> {{ $producto->tipo ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="stock-badge stock-badge-{{ $colorBadge }}">
                                                <i class="fas fa-cubes"></i>
                                                {{ number_format($stock) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 0.9rem; color: #6b7280; font-weight: 600;">
                                                {{ ucfirst($producto->unidad_medida ?? 'Unidad') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-modern badge-{{ $nivel === 'alto' ? 'success' : ($nivel === 'medio' ? 'warning' : ($nivel === 'bajo' ? 'danger' : 'secondary')) }}">
                                                <i class="fas {{ $estadoIcon }}"></i>
                                                {{ $estadoLabel }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.productos.edit', $producto) }}"
                                                   class="btn-action btn-action-edit"
                                                   title="Editar producto">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Editar</span>
                                                </a>
                                                <form action="{{ route('admin.productos.destroy', $producto) }}"
                                                      method="POST"
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn-action btn-action-delete"
                                                            title="Eliminar producto">
                                                        <i class="fas fa-trash"></i>
                                                        <span>Eliminar</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(!$hasProducts)
                                    <tr>
                                        <td colspan="6">
                                            <div class="text-center py-5">
                                                <i class="fas fa-inbox text-gray-300" style="font-size: 4rem; display: block; margin-bottom: 1rem;"></i>
                                                <p style="color: #6b7280; font-weight: 600; font-size: 1.1rem;">No hay productos registrados</p>
                                                <a href="{{ route('admin.productos.create') }}" class="btn-modern btn-primary mt-3">
                                                    <i class="fas fa-plus-circle"></i>
                                                    Crear Primer Producto
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable moderno
        ModernComponents.initModernDataTable('#inventarioTable', {
            order: [[2, 'desc']], // Ordenar por stock (mayor a menor)
            pageLength: 25,
            columnDefs: [
                { targets: [2, 4, 5], orderable: false }
            ]
        });

        // Confirmación de eliminación
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            ModernComponents.confirmDelete(
                'Esta acción eliminará permanentemente el producto y todo su historial.',
                '¿Eliminar producto?'
            ).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Animación de entrada
        $('.modern-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(30px)'
            });

            setTimeout(() => {
                $(this).css({
                    'opacity': '1',
                    'transform': 'translateY(0)',
                    'transition': 'all 0.6s ease-out'
                });
            }, index * 100);
        });

        // Tooltip
        $('[title]').tooltip();

        // Auto-refresh cada 60 segundos para stock en tiempo real
        setTimeout(() => {
            location.reload();
        }, 60000);

        // Notificar al usuario sobre auto-refresh
        setTimeout(() => {
            ModernComponents.showNotification('info', 'El inventario se actualiza automáticamente cada 60 segundos', 'Actualización automática');
        }, 2000);
    });
</script>
@endpush

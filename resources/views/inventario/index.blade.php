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

    /* Tabla moderna con diseño mejorado */
    .inventario-table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .inventario-table-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .inventario-table-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .inventario-table-header h3 i {
        font-size: 1.8rem;
    }

    .inventario-table-body {
        padding: 2rem;
    }

    /* Estilos de tabla mejorados */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.95rem;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .modern-table thead th {
        padding: 1rem 1.25rem;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #334155;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
    }

    .modern-table thead th.text-center {
        text-align: center;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .modern-table tbody td {
        padding: 1.25rem;
        color: #475569;
        vertical-align: middle;
    }

    /* Badge de stock con colores vibrantes */
    .stock-value {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.2rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .stock-minimo-value {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }

    /* Iconos de producto */
    .producto-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        font-size: 1.5rem;
        margin-right: 1rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .producto-nombre {
        font-weight: 700;
        font-size: 1.1rem;
        color: #1e293b;
        margin: 0;
    }

    .descripcion-text {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }

    .unidad-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0c4a6e;
    }

    /* Botón nuevo producto mejorado */
    .btn-nuevo-producto {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }

    .btn-nuevo-producto:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-nuevo-producto i {
        font-size: 1.1rem;
    }

    /* DataTables personalization */
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .dataTables_wrapper .dataTables_length select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 2rem 0.5rem 1rem;
        font-size: 0.95rem;
    }

    /* Animación de entrada */
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

    .inventario-table-card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Botones de acción */
    .action-buttons-group {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        text-decoration: none;
    }

    .btn-action i {
        font-size: 0.9rem;
    }

    .btn-action-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-action-edit:hover {
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

    /* ==================== ESTILOS RESPONSIVE ==================== */

    /* Tablet (992px y menos) */
    @media (max-width: 992px) {
        .inventario-table-header {
            padding: 1rem 1.5rem;
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .inventario-table-header h3 {
            font-size: 1.25rem;
        }

        .inventario-table-body {
            padding: 1rem;
        }

        .modern-table thead th {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }

        .modern-table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .producto-icon {
            width: 36px;
            height: 36px;
            font-size: 1.1rem;
            margin-right: 0.5rem;
        }

        .producto-nombre {
            font-size: 0.95rem;
        }

        .stock-value {
            padding: 0.4rem 0.8rem;
            font-size: 1rem;
        }

        .stock-minimo-value {
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
        }
    }

    /* Móvil (768px y menos) */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .inventario-table-card {
            border-radius: 12px;
        }

        .inventario-table-header {
            padding: 1rem;
        }

        .inventario-table-header h3 {
            font-size: 1.1rem;
        }

        .inventario-table-header h3 i {
            font-size: 1.3rem;
        }

        .btn-nuevo-producto {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            width: 100%;
            justify-content: center;
        }

        .inventario-table-body {
            padding: 0.75rem;
        }

        /* Ocultar columnas menos importantes en móvil */
        .modern-table thead th:nth-child(2),
        .modern-table tbody td:nth-child(2) {
            display: none; /* Ocultar descripción */
        }

        .modern-table thead th:nth-child(5),
        .modern-table tbody td:nth-child(5) {
            display: none; /* Ocultar stock mínimo */
        }

        .modern-table thead th {
            padding: 0.6rem 0.4rem;
            font-size: 0.7rem;
        }

        .modern-table tbody td {
            padding: 0.6rem 0.4rem;
            font-size: 0.85rem;
        }

        .producto-icon {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
            margin-right: 0.4rem;
            border-radius: 8px;
        }

        .producto-nombre {
            font-size: 0.85rem;
        }

        .unidad-badge {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }

        .stock-value {
            padding: 0.35rem 0.6rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .action-buttons-group {
            flex-direction: column;
            gap: 0.4rem;
        }

        .btn-action {
            padding: 0.5rem 0.7rem;
            font-size: 0.75rem;
            width: 100%;
            justify-content: center;
        }

        .action-buttons-group .form-delete-producto {
            width: 100%;
        }

        .action-buttons-group .form-delete-producto .btn-action {
            width: 100%;
        }

        /* DataTables responsive */
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            float: none !important;
            text-align: center !important;
            margin-bottom: 0.75rem !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            max-width: 250px;
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
            font-size: 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            text-align: center !important;
            margin-top: 0.75rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.6rem !important;
            font-size: 0.75rem !important;
        }
    }

    /* Móvil pequeño (480px y menos) */
    @media (max-width: 480px) {
        .container-fluid {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }

        .inventario-table-card {
            border-radius: 8px;
        }

        .inventario-table-header {
            padding: 0.75rem;
        }

        .inventario-table-header h3 {
            font-size: 1rem;
        }

        .btn-nuevo-producto {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

        .inventario-table-body {
            padding: 0.5rem;
        }

        /* Ocultar también la columna de unidad en pantallas muy pequeñas */
        .modern-table thead th:nth-child(3),
        .modern-table tbody td:nth-child(3) {
            display: none;
        }

        .modern-table thead th {
            padding: 0.5rem 0.3rem;
            font-size: 0.65rem;
        }

        .modern-table tbody td {
            padding: 0.5rem 0.3rem;
        }

        .producto-icon {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
            margin-right: 0.3rem;
        }

        .producto-nombre {
            font-size: 0.8rem;
        }

        .stock-value {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
            gap: 0.25rem;
        }

        .stock-value i {
            font-size: 0.7rem;
        }

        .btn-action {
            padding: 0.4rem 0.5rem;
            font-size: 0.7rem;
        }

        .btn-action i {
            font-size: 0.75rem;
        }

        /* Solo mostrar icono en los botones en pantallas muy pequeñas */
        .btn-action span.btn-text {
            display: none;
        }

        .dataTables_wrapper .dataTables_filter input {
            max-width: 180px;
            font-size: 0.8rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3rem 0.5rem !important;
            font-size: 0.7rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            <!-- Tarjeta de Inventario -->
            <div class="inventario-table-card">
                <!-- Encabezado de la tabla -->
                <div class="inventario-table-header">
                    <h3>
                        <i class="fas fa-boxes"></i>
                        Stock por Producto
                    </h3>
                    @if(auth()->user()->rol->nombre === 'admin')
                    <a href="{{ route('inventario.productos.create') }}" class="btn-nuevo-producto">
                        <i class="fas fa-plus-circle"></i>
                        Nuevo Producto
                    </a>
                    @endif
                </div>

                <!-- Cuerpo de la tabla -->
                <div class="inventario-table-body">
                    <div class="table-responsive">
                        <table class="modern-table" id="inventarioTable">
                            <thead>
                                <tr>
                                    <th>Nombre del Producto</th>
                                    <th>Descripción</th>
                                    <th>Unidad de Medida</th>
                                    <th class="text-center">Stock de Productos</th>
                                    <th class="text-center">Stock Mínimo</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos ?? [] as $producto)
                                    @php
                                        $stock = \App\Models\Inventario::stockDisponible($producto->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="producto-icon">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div>
                                                    <div class="producto-nombre">
                                                        {{ $producto->nombre }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="descripcion-text">
                                                {{ $producto->descripcion ?? 'Sin descripción' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="unidad-badge">
                                                <i class="fas fa-ruler-combined"></i>
                                                {{ ucfirst($producto->unidad_medida ?? 'Unidad') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="stock-value">
                                                <i class="fas fa-cubes"></i>
                                                {{ number_format($stock) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="stock-minimo-value">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ number_format($producto->stock_minimo ?? 0) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if(auth()->user()->rol->nombre === 'admin')
                                            <div class="action-buttons-group">
                                                <a href="{{ route('inventario.productos.edit', $producto->id) }}"
                                                   class="btn-action btn-action-edit"
                                                   title="Editar producto">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="btn-text">Editar</span>
                                                </a>
                                                <form action="{{ route('inventario.productos.destroy', $producto->id) }}"
                                                      method="POST"
                                                      class="d-inline form-delete-producto"
                                                      onsubmit="return confirm('¿Está seguro de eliminar este producto?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn-action btn-action-delete"
                                                            title="Eliminar producto">
                                                        <i class="fas fa-trash-alt"></i>
                                                        <span class="btn-text">Eliminar</span>
                                                    </button>
                                                </form>
                                            </div>
                                            @else
                                            <span class="text-gray-400 text-sm">
                                                <i class="fas fa-lock mr-1"></i>
                                                Solo lectura
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
            order: [[0, 'asc']], // Ordenar por nombre de producto
            pageLength: 25,
            columnDefs: [
                { targets: [3, 4], orderable: true }, // Stock de productos y Stock mínimo ordenables
                { targets: [5], orderable: false } // Acciones no ordenables
            ],
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ productos",
                info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
                infoEmpty: "Mostrando 0 a 0 de 0 productos",
                infoFiltered: "(filtrado de _MAX_ productos totales)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                emptyTable: "No hay productos registrados"
            }
        });

        // Tooltip
        $('[title]').tooltip();
    });
</script>
@endpush

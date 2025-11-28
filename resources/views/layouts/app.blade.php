<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agua Colegial') - Sistema de Gestión</title>

    <!-- Tailwind CSS -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery (Required for Select2 and DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 Bootstrap Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilos Globales Unificados -->
    <link rel="stylesheet" href="{{ asset('css/global-styles.css') }}">

    <style>
        /* Tamaño de fuente base reducido al 80% */
        html {
            font-size: 80%;
        }

        /* Variables Institucionales Agua Colegial */
        :root {
            --azul-oscuro-institucional: #073d71;
            --azul-claro-institucional: #1a8cff;
            --blanco: #ffffff;
            --azul-hover: #0a4d8f;
            --azul-activo: #0d5ba8;
        }

        /* Sidebar Institucional - Estático */
        .sidebar {
            width: 220px;
            background: var(--azul-oscuro-institucional);
            border-right: 2px solid #0a4d8f;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 40;
            overflow-y: auto;
        }

        /* Logo Header Institucional */
        .sidebar-header {
            background: linear-gradient(135deg, #073d71 0%, #0a4d8f 100%);
            padding: 1rem 1rem;
            border-bottom: 2px solid rgba(26, 140, 255, 0.2);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-logo-icon {
            width: 80px;
            height: 80px;
            background: transparent;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            flex-shrink: 0;
            padding: 0;
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .sidebar-logo-text {
            color: var(--blanco);
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            line-height: 1.3;
        }


        /* Section Headers Institucional */
        .sidebar-section-header {
            padding: 0.75rem 1.5rem 0.4rem;
            margin-top: 0.75rem;
            border-top: 2px solid rgba(26, 140, 255, 0.15);
        }

        .sidebar-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--blanco);
            opacity: 0.7;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-section-title i {
            color: var(--azul-claro-institucional);
            font-size: 0.75rem;
        }

        /* Menu Links Institucional */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.9rem;
            margin: 0.15rem 0.8rem;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--blanco);
            font-weight: 600;
            font-size: 0.8rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: var(--azul-claro-institucional);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        .sidebar-link:hover::before {
            transform: scaleY(1);
        }

        .sidebar-link:hover {
            background: var(--azul-hover);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(26, 140, 255, 0.25);
        }

        .sidebar-link.active {
            background: var(--azul-activo);
            box-shadow: 0 4px 20px rgba(26, 140, 255, 0.4);
        }

        .sidebar-link.active::before {
            transform: scaleY(1);
        }

        .sidebar-link i {
            font-size: 0.95rem;
            width: 20px;
            color: var(--azul-claro-institucional);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-link:hover i {
            transform: scale(1.1);
            color: #3da4ff;
        }

        .sidebar-link.active i {
            color: var(--blanco);
            animation: iconBounce 0.6s ease;
        }

        @keyframes iconBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        .sidebar-text {
            margin-left: 0.65rem;
            white-space: nowrap;
            transition: opacity 0.3s ease;
            font-size: 0.8rem;
        }

        /* User Section Institucional - Mejorado */
        .sidebar-user {
            border-top: 2px solid rgba(26, 140, 255, 0.3);
            padding: 1rem;
            background: linear-gradient(135deg, #041e36 0%, #062e54 50%, #073d71 100%);
        }

        .user-profile-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 0.9rem;
            margin-bottom: 0.8rem;
            border: 1px solid rgba(26, 140, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .user-profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.7rem;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--azul-claro-institucional), #3da4ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blanco);
            font-weight: 900;
            font-size: 1.1rem;
            box-shadow: 0 6px 20px rgba(26, 140, 255, 0.5);
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-user-info {
            flex: 1;
            margin-left: 0.8rem;
            min-width: 0;
        }

        .sidebar-user-name {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--blanco);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: var(--azul-claro-institucional);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .sidebar-user-role i {
            font-size: 0.6rem;
        }

        .user-profile-stats {
            display: flex;
            justify-content: center;
            padding-top: 0.6rem;
            border-top: 1px solid rgba(26, 140, 255, 0.2);
        }

        .user-profile-stats .stat-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.7rem;
            color: #4ade80;
            font-weight: 600;
        }

        .user-profile-stats .stat-item i {
            font-size: 0.6rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .sidebar-logout-btn {
            width: 100%;
            padding: 0.7rem;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: var(--blanco);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        .sidebar-logout-btn:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.6);
        }

        .sidebar-logout-btn:active {
            transform: translateY(0);
        }

        .sidebar-logout-btn i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .sidebar-logout-btn:hover i {
            transform: translateX(3px);
        }

        /* Scrollbar Institucional */
        .sidebar nav::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar nav::-webkit-scrollbar-thumb {
            background: var(--azul-claro-institucional);
            border-radius: 10px;
        }

        .sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #3da4ff;
        }

        /* Main Content */
        .main-content {
            margin-left: 220px;
        }

        /* ==========================================
           Responsive Design - Mobile & Tablet
           ========================================== */

        /* Mobile Menu Toggle Button */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 50;
            background: var(--azul-oscuro-institucional);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 0.8rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            font-size: 1.1rem;
        }

        .mobile-menu-toggle:hover {
            background: var(--azul-hover);
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 39;
            backdrop-filter: blur(2px);
        }

        .mobile-overlay.active {
            display: block;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            .main-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            /* Show mobile menu button */
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Sidebar hidden by default */
            .sidebar {
                position: fixed !important;
                left: -300px !important;
                width: 280px !important;
                transition: left 0.3s ease !important;
                z-index: 40 !important;
                height: 100vh !important;
                transform: none !important;
            }

            .sidebar.mobile-open {
                left: 0 !important;
            }

            /* Main content full width */
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Header adjustments */
            header {
                padding: 1rem !important;
                padding-left: 3.5rem !important;
            }

            header h1 {
                font-size: 1.2rem !important;
                margin-bottom: 0.25rem;
            }

            header p {
                font-size: 0.75rem !important;
            }

            /* Main content area */
            main.flex-1 {
                padding: 1rem !important;
            }

            /* Cards row - horizontal scroll */
            .cards-row {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 0.5rem !important;
                padding: 0.5rem !important;
                overflow-x: auto;
            }

            .stat-card {
                width: 100% !important;
                height: auto !important;
                min-height: 85px;
            }

            .stat-card-header {
                padding: 0.4rem !important;
            }

            .stat-card-icon {
                font-size: 1rem !important;
            }

            .stat-card-title {
                font-size: 0.6rem !important;
            }

            .stat-card-body {
                padding: 0.3rem !important;
            }

            .stat-number {
                font-size: 1.1rem !important;
            }

            .stat-label {
                font-size: 0.5rem !important;
            }

            /* Info sections */
            .info-section {
                padding: 1rem !important;
                margin-bottom: 1rem;
                border-radius: 12px;
            }

            .section-header {
                margin-bottom: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }

            .section-header h4 {
                font-size: 1rem !important;
            }

            .section-header i {
                font-size: 1.2rem !important;
            }

            /* Grid layouts */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .col-lg-4, .col-lg-6, .col-md-6 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            /* Movement items */
            .movement-item {
                padding: 0.6rem !important;
                margin-bottom: 0.4rem !important;
            }

            /* Tables */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            table {
                font-size: 0.8rem !important;
                min-width: 600px;
            }

            table th, table td {
                padding: 0.6rem 0.5rem !important;
            }

            /* Buttons */
            .btn {
                font-size: 0.8rem !important;
                padding: 0.5rem 1rem !important;
            }

            .btn-sm {
                font-size: 0.7rem !important;
                padding: 0.4rem 0.6rem !important;
            }

            /* Forms */
            input, select, textarea {
                font-size: 16px !important;
            }

            .form-control, .form-select {
                padding: 0.6rem !important;
            }

            label {
                font-size: 0.85rem !important;
                margin-bottom: 0.3rem !important;
            }

            /* DataTables */
            .dataTables_wrapper {
                font-size: 0.8rem;
            }

            .dataTables_filter {
                margin-bottom: 0.5rem !important;
            }

            .dataTables_filter input {
                width: 150px !important;
                padding: 0.4rem !important;
            }

            .dataTables_length {
                margin-bottom: 0.5rem !important;
            }

            .dataTables_info {
                font-size: 0.7rem !important;
                padding-top: 0.5rem !important;
            }

            .dataTables_paginate {
                padding-top: 0.5rem !important;
            }

            .paginate_button {
                padding: 0.3rem 0.6rem !important;
                font-size: 0.75rem !important;
            }

            /* Alerts */
            .alert-card {
                padding: 0.75rem !important;
            }

            .alert-card-title {
                font-size: 0.85rem !important;
            }

            .alert-card-stock {
                font-size: 1.3rem !important;
            }

            /* SweetAlert */
            .swal2-popup {
                width: 90% !important;
                font-size: 0.9rem !important;
                padding: 1.5rem !important;
            }

            /* Hide on mobile */
            .hide-on-mobile {
                display: none !important;
            }
        }

        @media (max-width: 576px) {
            /* Extra small screens */
            header {
                padding-left: 3rem !important;
            }

            header h1 {
                font-size: 1rem !important;
            }

            header p {
                display: none;
            }

            /* Cards 2 columns */
            .cards-row {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .stat-card {
                min-height: 75px;
            }

            .stat-card-icon {
                font-size: 0.9rem !important;
            }

            .stat-card-title {
                font-size: 0.55rem !important;
            }

            .stat-number {
                font-size: 0.95rem !important;
            }

            /* Stack buttons */
            .flex.gap-2, .flex.gap-3, .flex.space-x-2 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }

            .btn {
                width: 100% !important;
                justify-content: center !important;
            }

            /* Full width containers */
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            main.flex-1 {
                padding: 0.75rem !important;
            }

            /* Smaller info sections */
            .info-section {
                padding: 0.75rem !important;
            }

            .section-header h4 {
                font-size: 0.9rem !important;
            }

            /* Movement items compact */
            .movement-item {
                padding: 0.5rem !important;
                font-size: 0.8rem;
            }

            /* Tables even smaller */
            table {
                font-size: 0.75rem !important;
            }

            table th, table td {
                padding: 0.5rem 0.4rem !important;
            }
        }

        @media (max-width: 400px) {
            /* Very small screens */
            .mobile-menu-toggle {
                top: 0.5rem;
                left: 0.5rem;
                padding: 0.5rem 0.7rem;
                font-size: 1rem;
            }

            header {
                padding-left: 2.8rem !important;
            }

            header h1 {
                font-size: 0.9rem !important;
            }

            .cards-row {
                gap: 0.4rem !important;
            }

            .stat-card {
                min-height: 70px;
            }

            .stat-card-icon {
                font-size: 0.85rem !important;
                margin-bottom: 2px !important;
            }

            .stat-card-title {
                font-size: 0.5rem !important;
            }

            .stat-number {
                font-size: 0.85rem !important;
            }

            .stat-label {
                font-size: 0.45rem !important;
            }

            /* Even more compact */
            .info-section {
                padding: 0.6rem !important;
            }

            .section-header {
                margin-bottom: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .section-header h4 {
                font-size: 0.85rem !important;
            }

            .section-header i {
                font-size: 1rem !important;
            }

            .movement-item {
                padding: 0.4rem !important;
                font-size: 0.75rem;
            }

            table {
                font-size: 0.7rem !important;
            }

            .btn {
                font-size: 0.75rem !important;
                padding: 0.4rem 0.8rem !important;
            }
        }

        /* ==========================================
           Responsive para Módulos y Formularios
           ========================================== */

        /* Cards y contenedores de módulos */
        .bg-white.rounded-lg.shadow-md,
        .bg-white.rounded-lg.shadow,
        .card {
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            /* Contenedores principales de módulos */
            .bg-white.rounded-lg.shadow-md,
            .bg-white.rounded-lg.shadow,
            .card {
                border-radius: 8px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Padding de contenedores */
            .p-6, .p-8 {
                padding: 1rem !important;
            }

            .px-6, .px-8 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .py-6, .py-8 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            /* Títulos de módulos */
            .text-2xl {
                font-size: 1.25rem !important;
            }

            .text-xl {
                font-size: 1.1rem !important;
            }

            .text-lg {
                font-size: 1rem !important;
            }

            /* Grids de formularios */
            .grid-cols-2,
            .grid-cols-3,
            .grid-cols-4,
            .md\:grid-cols-2,
            .md\:grid-cols-3,
            .md\:grid-cols-4,
            .lg\:grid-cols-2,
            .lg\:grid-cols-3,
            .lg\:grid-cols-4 {
                grid-template-columns: 1fr !important;
            }

            /* Espaciado en grids */
            .gap-4, .gap-6 {
                gap: 0.75rem !important;
            }

            /* Flex containers */
            .flex.justify-between {
                flex-direction: column;
                gap: 0.75rem;
            }

            .flex.items-center.justify-between {
                align-items: flex-start !important;
            }

            /* Botones en línea */
            .flex.gap-2 .btn,
            .flex.gap-3 .btn,
            .space-x-2 .btn,
            .space-x-3 .btn {
                margin-bottom: 0.5rem;
            }

            /* Tablas en módulos */
            .overflow-x-auto {
                margin: 0 -1rem;
                padding: 0 1rem;
                width: calc(100% + 2rem);
            }

            /* Inputs y selects */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="time"],
            input[type="datetime-local"],
            select,
            textarea {
                font-size: 16px !important;
                padding: 0.6rem !important;
                border-radius: 6px !important;
            }

            /* Labels */
            label {
                font-size: 0.85rem !important;
                margin-bottom: 0.25rem !important;
            }

            /* Breadcrumbs */
            nav.text-sm {
                font-size: 0.75rem !important;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 0.5rem;
            }

            /* Badges */
            .badge,
            span.px-2.inline-flex,
            span.px-3.py-1 {
                font-size: 0.65rem !important;
                padding: 0.2rem 0.4rem !important;
            }

            /* Iconos en botones */
            .btn i,
            button i {
                font-size: 0.8rem !important;
            }

            /* Acciones de tabla */
            .flex.space-x-1,
            .flex.space-x-2,
            .flex.gap-1 {
                flex-wrap: wrap;
                gap: 0.25rem !important;
            }

            /* Botones de acción pequeños */
            .btn-sm,
            a.text-blue-600,
            a.text-red-600,
            a.text-green-600 {
                padding: 0.3rem 0.5rem !important;
                font-size: 0.7rem !important;
            }

            /* Modales y diálogos */
            .modal-content,
            .swal2-modal {
                margin: 0.5rem !important;
                max-width: calc(100vw - 1rem) !important;
            }

            /* Alertas y notificaciones */
            .alert,
            [role="alert"] {
                padding: 0.75rem !important;
                font-size: 0.85rem !important;
                border-radius: 6px !important;
            }

            /* Info boxes */
            .bg-blue-50,
            .bg-yellow-50,
            .bg-green-50,
            .bg-red-50 {
                padding: 0.75rem !important;
                font-size: 0.8rem !important;
            }

            /* Descripción/ayuda text */
            .text-sm.text-gray-500,
            .text-sm.text-gray-600,
            .text-xs {
                font-size: 0.7rem !important;
            }

            /* Empty states */
            .text-center.py-8,
            .text-center.py-12 {
                padding: 2rem 1rem !important;
            }

            .text-center.py-8 i,
            .text-center.py-12 i {
                font-size: 2rem !important;
            }
        }

        @media (max-width: 576px) {
            /* Extra pequeño */
            .p-6, .p-8 {
                padding: 0.75rem !important;
            }

            .text-2xl {
                font-size: 1.1rem !important;
            }

            .text-xl {
                font-size: 1rem !important;
            }

            /* Ocultar texto en botones pequeños, solo iconos */
            .btn-sm span,
            .btn-xs span {
                display: none;
            }

            /* Hacer botones de acción solo iconos */
            td .btn,
            td a.btn {
                padding: 0.4rem !important;
                min-width: auto !important;
            }

            /* Tablas más compactas */
            table th,
            table td {
                padding: 0.4rem 0.3rem !important;
            }

            /* Select2 más compacto */
            .select2-container {
                font-size: 0.85rem !important;
            }

            .select2-selection {
                min-height: 38px !important;
            }
        }

        @media (max-width: 400px) {
            /* Muy pequeño */
            .p-6, .p-8 {
                padding: 0.5rem !important;
            }

            .text-2xl {
                font-size: 1rem !important;
            }

            .gap-4, .gap-6 {
                gap: 0.5rem !important;
            }

            input, select, textarea {
                padding: 0.5rem !important;
            }
        }

        /* ==========================================
           Estilos para Select2 con Tailwind
           ========================================== */
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            min-height: 42px !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus,
        .select2-container--bootstrap-5 .select2-selection--single:focus {
            border-color: #1e3a8a !important;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1) !important;
            outline: none !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #1e3a8a !important;
            color: white !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding-left: 0 !important;
            color: #374151 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--bootstrap-5 .select2-selection__clear {
            color: #dc2626 !important;
            font-size: 1.2rem !important;
            margin-right: 10px !important;
        }

        /* ==========================================
           Estilos para DataTables con Tailwind
           ========================================== */
        .dataTables_wrapper {
            padding: 1rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
            outline: none;
        }

        .dataTables_wrapper table.dataTable thead th {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%) !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0.75rem 1rem !important;
            border-bottom: 2px solid #1e3a8a !important;
        }

        .dataTables_wrapper table.dataTable tbody tr {
            transition: all 0.2s ease;
        }

        .dataTables_wrapper table.dataTable tbody tr:hover {
            background-color: #f3f4f6 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            margin: 0 0.125rem;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #1e3a8a;
            border-color: #1e3a8a;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%) !important;
            border-color: #1e3a8a !important;
            color: white !important;
        }

        /* ==========================================
           Estilos para Toastr (notificaciones)
           ========================================== */
        #toast-container > div {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        #toast-container .toast-success {
            background-color: #10b981;
        }

        #toast-container .toast-error {
            background-color: #dc2626;
        }

        #toast-container .toast-warning {
            background-color: #f59e0b;
        }

        #toast-container .toast-info {
            background-color: #3b82f6;
        }

        /* ==========================================
           Badges y Tags personalizados
           ========================================== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.25rem;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-primary {
            background-color: #dbeafe;
            color: #1e3a8a;
        }

        /* ==========================================
           Botones mejorados
           ========================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            border-color: #9ca3af;
        }

        /* ==========================================
           Cards mejorados
           ========================================== */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ==========================================
           Animaciones personalizadas
           ========================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease;
        }

        /* ==========================================
           Scrollbar personalizado
           ========================================== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1e40af;
        }

        /* ==========================================
           Estilos para Notificaciones de Stock
           ========================================== */
        #stock-notification {
            position: fixed;
            top: 80px;
            right: 20px;
            max-width: 400px;
            z-index: 9999;
            transform: translateX(450px);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #stock-notification.show {
            transform: translateX(0);
        }

        .notification-item {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Notificación de Stock Bajo -->
    <div id="stock-notification" class="shadow-2xl">
        <div class="bg-white rounded-lg overflow-hidden border-l-4 border-red-500">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-500 to-red-600">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-white text-2xl animate-pulse"></i>
                    <div>
                        <h3 class="text-white font-bold text-lg">Alerta de Stock Bajo</h3>
                        <p class="text-red-100 text-sm">Productos por debajo del umbral mínimo</p>
                    </div>
                </div>
                <button onclick="closeStockNotification()" class="text-white hover:text-red-100 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="stock-notification-content" class="p-4 max-h-96 overflow-y-auto">
                <!-- Contenido dinámico -->
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t">
                <a href="{{ route('inventario.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    Ver inventario completo
                </a>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Toggle Button -->
    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="Abrir menú">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="mobile-overlay"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Estático -->
        <aside id="sidebar" class="sidebar flex flex-col">
            <!-- Logo -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <img src="{{ asset('images/logo.png') }}" alt="Agua Colegial Logo">
                    </div>
                    <span class="sidebar-logo-text">Agua Colegial</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-2">
                    @php
                        $rol = auth()->user()->rol->nombre ?? 'guest';
                        $currentRoute = request()->route()->getName() ?? '';
                    @endphp

                    <!-- Menú Administrador -->
                    @if($rol === 'admin')
                        <!-- 1. Inicio -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home w-5"></i>
                                <span class="ml-3 sidebar-text">Inicio</span>
                            </a>
                        </li>

                        <!-- Sección: Principal -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-star text-xs"></i>
                                <span class="sidebar-text">Principal</span>
                            </p>
                        </li>

                        <!-- 2. Registro del Personal -->
                        <li>
                            <a href="{{ route('control.asistencia-semanal.registro-rapido') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_contains($currentRoute, 'registro-rapido') ? 'active' : '' }}">
                                <i class="fas fa-user-clock w-5"></i>
                                <span class="ml-3 sidebar-text">Registro del Personal</span>
                            </a>
                        </li>

                        <!-- 3. Inventario General -->
                        <li>
                            <a href="{{ route('inventario.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.index') ? 'active' : '' }}">
                                <i class="fas fa-warehouse w-5"></i>
                                <span class="ml-3 sidebar-text">Inventario General</span>
                            </a>
                        </li>

                        <!-- Sección: Controles -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-clipboard-check text-xs"></i>
                                <span class="sidebar-text">Controles</span>
                            </p>
                        </li>

                        <!-- 4. Productos Producidos Diarios -->
                        <li>
                            <a href="{{ route('control.produccion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.produccion') ? 'active' : '' }}">
                                <i class="fas fa-industry w-5"></i>
                                <span class="ml-3 sidebar-text">Productos Producidos Diarios</span>
                            </a>
                        </li>

                        <!-- 5. Salidas Productos Diarios -->
                        <li>
                            <a href="{{ route('control.salidas.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.salidas') ? 'active' : '' }}">
                                <i class="fas fa-truck-loading w-5"></i>
                                <span class="ml-3 sidebar-text">Salidas Productos Diarios</span>
                            </a>
                        </li>

                        <!-- 6. Control de Insumos -->
                        <li>
                            <a href="{{ route('control.insumos.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.insumos') ? 'active' : '' }}">
                                <i class="fas fa-box-open w-5"></i>
                                <span class="ml-3 sidebar-text">Control de Insumos</span>
                            </a>
                        </li>

                        <!-- 7. Mantenimiento de Equipo -->
                        <li>
                            <a href="{{ route('control.mantenimiento.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.mantenimiento') ? 'active' : '' }}">
                                <i class="fas fa-tools w-5"></i>
                                <span class="ml-3 sidebar-text">Mantenimiento de Equipo</span>
                            </a>
                        </li>

                        <!-- 8. Limpieza de Tanques de Agua -->
                        <li>
                            <a href="{{ route('control.tanques.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.tanques') ? 'active' : '' }}">
                                <i class="fas fa-water w-5"></i>
                                <span class="ml-3 sidebar-text">Limpieza Tanques de Agua</span>
                            </a>
                        </li>

                        <!-- 9. Limpieza de Fosa Séptica -->
                        <li>
                            <a href="{{ route('control.fosa-septica.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.fosa-septica') ? 'active' : '' }}">
                                <i class="fas fa-toilet w-5"></i>
                                <span class="ml-3 sidebar-text">Limpieza Fosa Séptica</span>
                            </a>
                        </li>

                        <!-- 10. Control de Fumigación -->
                        <li>
                            <a href="{{ route('control.fumigacion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.fumigacion') ? 'active' : '' }}">
                                <i class="fas fa-spray-can w-5"></i>
                                <span class="ml-3 sidebar-text">Control de Fumigación</span>
                            </a>
                        </li>

                        <!-- Sección: Gestión -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-cog text-xs"></i>
                                <span class="sidebar-text">Gestión</span>
                            </p>
                        </li>

                        <!-- 11. Gestión de Vehículos -->
                        <li>
                            <a href="{{ route('admin.vehiculos.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.vehiculos') ? 'active' : '' }}">
                                <i class="fas fa-car w-5"></i>
                                <span class="ml-3 sidebar-text">Gestión de Vehículos</span>
                            </a>
                        </li>

                        <!-- 12. Reportes -->
                        <li>
                            <a href="{{ route('admin.reportes.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.reportes') ? 'active' : '' }}">
                                <i class="fas fa-file-chart-line w-5"></i>
                                <span class="ml-3 sidebar-text">Reportes</span>
                            </a>
                        </li>

                        <!-- Sección: Administración -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-users-cog text-xs"></i>
                                <span class="sidebar-text">Administración</span>
                            </p>
                        </li>

                        <!-- 14. Configuración del Sistema -->
                        <li>
                            <a href="{{ route('admin.configuracion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.configuracion') ? 'active' : '' }}">
                                <i class="fas fa-cogs w-5"></i>
                                <span class="ml-3 sidebar-text">Configuración del Sistema</span>
                            </a>
                        </li>

                    @endif

                    <!-- Menú Rol Producción -->
                    @if($rol === 'produccion')
                        <!-- 1. Inicio -->
                        <li>
                            <a href="{{ route('control.produccion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.produccion.index') ? 'active' : '' }}">
                                <i class="fas fa-home w-5"></i>
                                <span class="ml-3 sidebar-text">Inicio</span>
                            </a>
                        </li>

                        <!-- 2. Inventario General -->
                        <li>
                            <a href="{{ route('inventario.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.index') ? 'active' : '' }}">
                                <i class="fas fa-warehouse w-5"></i>
                                <span class="ml-3 sidebar-text">Inventario</span>
                            </a>
                        </li>

                        <!-- 3. Registro del Personal -->
                        <li>
                            <a href="{{ route('control.asistencia-semanal.registro-rapido') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_contains($currentRoute, 'registro-rapido') ? 'active' : '' }}">
                                <i class="fas fa-user-clock w-5"></i>
                                <span class="ml-3 sidebar-text">Historia de su Registro</span>
                            </a>
                        </li>

                        <!-- Sección: Controles -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-clipboard-check text-xs"></i>
                                <span class="sidebar-text">Controles</span>
                            </p>
                        </li>

                        <!-- 4. Productos Producidos Diarios -->
                        <li>
                            <a href="{{ route('control.produccion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.produccion') ? 'active' : '' }}">
                                <i class="fas fa-industry w-5"></i>
                                <span class="ml-3 sidebar-text">Productos Producidos Diarios</span>
                            </a>
                        </li>

                        <!-- 5. Salidas Productos Diarios -->
                        <li>
                            <a href="{{ route('control.salidas.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.salidas') ? 'active' : '' }}">
                                <i class="fas fa-truck-loading w-5"></i>
                                <span class="ml-3 sidebar-text">Salidas Productos Diarios</span>
                            </a>
                        </li>

                        <!-- 6. Control de Insumos -->
                        <li>
                            <a href="{{ route('control.insumos.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.insumos') ? 'active' : '' }}">
                                <i class="fas fa-box-open w-5"></i>
                                <span class="ml-3 sidebar-text">Control de Insumos</span>
                            </a>
                        </li>

                        <!-- 7. Mantenimiento de Equipo -->
                        <li>
                            <a href="{{ route('control.mantenimiento.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.mantenimiento') ? 'active' : '' }}">
                                <i class="fas fa-tools w-5"></i>
                                <span class="ml-3 sidebar-text">Mantenimiento de Equipo</span>
                            </a>
                        </li>

                        <!-- 8. Limpieza de Tanques de Agua -->
                        <li>
                            <a href="{{ route('control.tanques.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.tanques') ? 'active' : '' }}">
                                <i class="fas fa-water w-5"></i>
                                <span class="ml-3 sidebar-text">Limpieza Tanques de Agua</span>
                            </a>
                        </li>

                        <!-- 9. Limpieza de Fosa Séptica -->
                        <li>
                            <a href="{{ route('control.fosa-septica.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.fosa-septica') ? 'active' : '' }}">
                                <i class="fas fa-toilet w-5"></i>
                                <span class="ml-3 sidebar-text">Limpieza Fosa Séptica</span>
                            </a>
                        </li>

                        <!-- 10. Control de Fumigación -->
                        <li>
                            <a href="{{ route('control.fumigacion.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'control.fumigacion') ? 'active' : '' }}">
                                <i class="fas fa-spray-can w-5"></i>
                                <span class="ml-3 sidebar-text">Control de Fumigación</span>
                            </a>
                        </li>

                        <!-- 11. Reportes -->
                        <li>
                            <a href="{{ route('admin.reportes.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.reportes') ? 'active' : '' }}">
                                <i class="fas fa-file-chart-line w-5"></i>
                                <span class="ml-3 sidebar-text">Reportes</span>
                            </a>
                        </li>

                    @endif

                    <!-- Menú Rol Chofer -->
                    @if($rol === 'chofer')
                        <!-- Sección: Vehículos -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-car text-xs"></i>
                                <span class="sidebar-text">Vehículos</span>
                            </p>
                        </li>

                        <li>
                            <a href="{{ route('admin.vehiculos.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'admin.vehiculos') ? 'active' : '' }}">
                                <i class="fas fa-car-side w-5"></i>
                                <span class="ml-3 sidebar-text">Gestión de Vehículos</span>
                            </a>
                        </li>

                    @endif

                    <!-- Menú Rol Inventario -->
                    @if($rol === 'inventario')
                        <!-- Dashboard Principal -->
                        <li>
                            <a href="{{ route('inventario.dashboard') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home w-5"></i>
                                <span class="ml-3 sidebar-text">Inicio</span>
                            </a>
                        </li>

                        <!-- Sección: Inventario -->
                        <li class="sidebar-section-header">
                            <p class="sidebar-section-title">
                                <i class="fas fa-boxes text-xs"></i>
                                <span class="sidebar-text">Gestión de Inventario</span>
                            </p>
                        </li>

                        <li>
                            <a href="{{ route('inventario.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.index') ? 'active' : '' }}">
                                <i class="fas fa-warehouse w-5"></i>
                                <span class="ml-3 sidebar-text">Inventario General</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('inventario.movimiento.create') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.movimiento.create') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt w-5"></i>
                                <span class="ml-3 sidebar-text">Registro de Movimientos</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('inventario.movimiento.historial') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'inventario.movimiento.historial') ? 'active' : '' }}">
                                <i class="fas fa-history w-5"></i>
                                <span class="ml-3 sidebar-text">Historial de Movimientos</span>
                            </a>
                        </li>

                    @endif

                    <!-- Sección: Mi Asistencia (Solo para roles que registran asistencia: producción, inventario) -->
                    @if($rol !== 'admin')
                        <li class="sidebar-section-header mt-4">
                            <p class="sidebar-section-title">
                                <i class="fas fa-user-clock text-xs"></i>
                                <span class="sidebar-text">Mi Asistencia</span>
                            </p>
                        </li>

                        <li>
                            <a href="{{ route('personal.asistencia.index') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ str_starts_with($currentRoute, 'personal.asistencia') ? 'active' : '' }}">
                                <i class="fas fa-clock w-5"></i>
                                <span class="ml-3 sidebar-text">Registro de Asistencia</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('personal.asistencia.historial') }}"
                               class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ $currentRoute === 'personal.asistencia.historial' ? 'active' : '' }}">
                                <i class="fas fa-history w-5"></i>
                                <span class="ml-3 sidebar-text">Mi Historial</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="sidebar-user">
                <div class="user-profile-card">
                    <div class="user-profile-header">
                        <div class="sidebar-user-avatar">
                            {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                        </div>
                        <div class="sidebar-user-info">
                            <p class="sidebar-user-name truncate">{{ auth()->user()->nombre }}</p>
                            <p class="sidebar-user-role">
                                <i class="fas fa-shield-alt mr-1"></i>
                                {{ ucfirst($rol) }}
                            </p>
                        </div>
                    </div>
                    <div class="user-profile-stats">
                        <div class="stat-item">
                            <i class="fas fa-clock"></i>
                            <span>En línea</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="sidebar-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div id="main-content" class="flex-1 flex flex-col overflow-hidden main-content">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-600">@yield('page-subtitle', '')</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm text-gray-600">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                            <p class="text-xs text-gray-500" id="current-time"></p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-bold">Errores de validación:</span>
                        </div>
                        <ul class="list-disc list-inside ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Componentes Modernos JS -->
    <script src="{{ asset('js/modern-components.js') }}"></script>

    <!-- Scripts Globales -->
    <script>
        // ==========================================
        // Configuración Global de CSRF Token
        // ==========================================
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        // ==========================================
        // Reloj en tiempo real
        // ==========================================
        function updateClock() {
            const clockElement = document.getElementById('current-time');
            if (clockElement) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }

        updateClock();
        setInterval(updateClock, 1000);

        // ==========================================
        // Configuración Global de Toastr
        // ==========================================
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // ==========================================
        // Configuración Global de DataTables en Español
        // ==========================================
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de un total de _MAX_ entradas)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad",
                    "collection": "Colección",
                    "colvisRestore": "Restaurar visibilidad",
                    "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                    "copySuccess": {
                        "1": "Copiada 1 fila al portapapeles",
                        "_": "Copiadas %ds filas al portapapeles"
                    },
                    "copyTitle": "Copiar al portapapeles",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "Mostrar todas las filas",
                        "_": "Mostrar %d filas"
                    },
                    "pdf": "PDF",
                    "print": "Imprimir",
                    "renameState": "Cambiar nombre",
                    "updateState": "Actualizar"
                },
                "decimal": ",",
                "thousands": "."
            }
        });

        // ==========================================
        // Configuración Global de Select2
        // ==========================================
        $(document).ready(function() {
            // Inicializar todos los select con clase .select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    inputTooShort: function(args) {
                        return "Por favor ingrese " + (args.minimum - args.input.length) + " o más caracteres";
                    },
                    loadingMore: function() {
                        return "Cargando más resultados...";
                    },
                    maximumSelected: function(args) {
                        return "Solo puede seleccionar " + args.maximum + " elemento(s)";
                    }
                }
            });

            // Select2 sin búsqueda para listas pequeñas
            $('.select2-no-search').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true,
                minimumResultsForSearch: Infinity
            });

            // Select2 para múltiple selección
            $('.select2-multiple').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Seleccione una o más opciones',
                allowClear: true,
                multiple: true
            });
        });

        // ==========================================
        // Configuración Adicional de DataTables
        // ==========================================
        $.extend($.fn.dataTable.defaults, {
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            order: [[0, 'desc']],
            drawCallback: function() {
                // Agregar animación a filas nuevas
                $('.dataTables_wrapper tr').addClass('animate__animated animate__fadeIn animate__faster');
            }
        });

        // ==========================================
        // Auto-hide de alertas tradicionales
        // ==========================================
        document.querySelectorAll('[role="alert"]').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('animate__animated', 'animate__fadeOut');
                setTimeout(() => alert.remove(), 1000);
            }, 5000);
        });

        // ==========================================
        // Mostrar notificaciones con Toastr desde sesión
        // ==========================================
        @if(session('success'))
            toastr.success('{{ session('success') }}', '¡Éxito!');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error');
        @endif

        @if(session('warning'))
            toastr.warning('{{ session('warning') }}', 'Advertencia');
        @endif

        @if(session('info'))
            toastr.info('{{ session('info') }}', 'Información');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', 'Error de Validación');
            @endforeach
        @endif

        // ==========================================
        // Utilidades Globales
        // ==========================================

        // Función para confirmar eliminación
        function confirmDelete(message = '¿Está seguro de eliminar este registro?') {
            return Swal.fire({
                title: '¿Está seguro?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
        }

        // Función para mostrar loader
        function showLoader(message = 'Procesando...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Función para cerrar loader
        function hideLoader() {
            Swal.close();
        }

        // Función para formatear números
        function formatNumber(number, decimals = 0) {
            return new Intl.NumberFormat('es-ES', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(number);
        }

        // Función para formatear moneda
        function formatCurrency(amount, currency = 'COP') {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: currency
            }).format(amount);
        }

        // ==========================================
        // Sistema de Notificaciones de Stock Bajo
        // ==========================================
        let notificationTimeout = null;
        let lastNotificationTime = null;
        const NOTIFICATION_INTERVAL = 5 * 60 * 1000; // 5 minutos en milisegundos

        function verificarAlertasStock() {
            // Solo verificar si el usuario tiene permisos de inventario
            @if(in_array(auth()->user()->rol->nombre ?? '', ['admin', 'inventario', 'produccion']))
                // Verificar si han pasado al menos 5 minutos desde la última notificación
                const now = Date.now();
                const storedTime = localStorage.getItem('lastStockNotification');

                if (storedTime) {
                    lastNotificationTime = parseInt(storedTime);
                }

                // Solo mostrar si es la primera vez o han pasado 5 minutos
                if (!lastNotificationTime || (now - lastNotificationTime) >= NOTIFICATION_INTERVAL) {
                    $.ajax({
                        url: '{{ route("inventario.api.verificar-alertas") }}',
                        method: 'GET',
                        success: function(response) {
                            if (response.total > 0) {
                                mostrarNotificacionStock(response.alertas);
                                // Guardar el tiempo de la última notificación
                                lastNotificationTime = now;
                                localStorage.setItem('lastStockNotification', now.toString());
                            }
                        },
                        error: function(xhr) {
                            console.error('Error al verificar alertas de stock:', xhr);
                        }
                    });
                }
            @endif
        }

        function mostrarNotificacionStock(alertas) {
            const contentDiv = document.getElementById('stock-notification-content');
            const notification = document.getElementById('stock-notification');

            if (!contentDiv || !notification) return;

            // Construir contenido HTML
            let html = '';

            alertas.forEach((alerta, index) => {
                let nivelColor = '';
                let nivelTexto = '';
                let nivelIcono = '';

                if (alerta.nivel_urgencia === 'critico') {
                    nivelColor = 'bg-red-100 border-red-400 text-red-800';
                    nivelTexto = 'Crítico';
                    nivelIcono = 'fa-exclamation-circle text-red-600';
                } else if (alerta.nivel_urgencia === 'alto') {
                    nivelColor = 'bg-orange-100 border-orange-400 text-orange-800';
                    nivelTexto = 'Alto';
                    nivelIcono = 'fa-exclamation-triangle text-orange-600';
                } else {
                    nivelColor = 'bg-yellow-100 border-yellow-400 text-yellow-800';
                    nivelTexto = 'Medio';
                    nivelIcono = 'fa-info-circle text-yellow-600';
                }

                // Calcular porcentaje del umbral
                const porcentaje = alerta.umbral > 0 ? Math.round((alerta.stock_actual / alerta.umbral) * 100) : 0;

                html += `
                    <div class="notification-item mb-3 p-3 rounded-lg border-l-4 ${nivelColor}" style="animation-delay: ${index * 0.1}s">
                        <div class="flex items-start gap-3">
                            <i class="fas ${nivelIcono} text-xl mt-1"></i>
                            <div class="flex-1">
                                <p class="font-bold text-sm">${alerta.nombre}</p>
                                <div class="mt-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs">Stock actual: <strong>${alerta.stock_actual} unidades</strong></span>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded ${nivelColor}">${nivelTexto}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600">Umbral mínimo: ${alerta.umbral} unidades</span>
                                        <span class="text-xs text-gray-600">${porcentaje}% del umbral</span>
                                    </div>
                                    <!-- Barra de progreso -->
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="h-1.5 rounded-full transition-all"
                                             style="width: ${Math.min(porcentaje, 100)}%; background-color: ${
                                                 alerta.nivel_urgencia === 'critico' ? '#dc2626' :
                                                 alerta.nivel_urgencia === 'alto' ? '#f97316' : '#facc15'
                                             }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            contentDiv.innerHTML = html;

            // Mostrar notificación
            notification.classList.add('show');

            // Auto-ocultar después de 20 segundos (aumentado para dar más tiempo de lectura)
            if (notificationTimeout) {
                clearTimeout(notificationTimeout);
            }

            notificationTimeout = setTimeout(() => {
                closeStockNotification();
            }, 20000);

            // Reproducir sonido de notificación (opcional)
            playNotificationSound();
        }

        function closeStockNotification() {
            const notification = document.getElementById('stock-notification');
            if (notification) {
                notification.classList.remove('show');
            }

            if (notificationTimeout) {
                clearTimeout(notificationTimeout);
                notificationTimeout = null;
            }
        }

        function playNotificationSound() {
            // Opcional: reproducir un sonido de alerta
            // const audio = new Audio('/sounds/notification.mp3');
            // audio.play().catch(err => console.log('No se pudo reproducir el sonido:', err));
        }

        // Verificar alertas al cargar la página
        $(document).ready(function() {
            verificarAlertasStock();

            // Verificar cada 5 minutos (300000 ms)
            setInterval(verificarAlertasStock, 300000);
        });

        // ==========================================
        // Mobile Menu Toggle
        // ==========================================
        (function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            if (!mobileMenuToggle || !sidebar || !mobileOverlay) {
                console.error('Mobile menu elements not found');
                return;
            }

            // Function to close sidebar
            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
                document.body.style.overflow = '';
            }

            // Function to open sidebar
            function openSidebar() {
                sidebar.classList.add('mobile-open');
                mobileOverlay.classList.add('active');
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
                document.body.style.overflow = 'hidden';
            }

            // Toggle sidebar on button click
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            // Close sidebar when clicking overlay
            mobileOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeSidebar();
            });

            // Close sidebar when clicking a menu link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                }
            });

            // Close sidebar on window resize if wider than mobile
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768 && sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

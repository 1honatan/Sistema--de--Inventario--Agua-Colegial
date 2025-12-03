# 19. CARPETA RESOURCES/ - VISTAS Y FRONTEND

## 📁 ESTRUCTURA RESOURCES/

```
resources/
├── css/
│   └── app.css               (Estilos TailwindCSS)
├── js/
│   └── app.js                (JavaScript principal)
└── views/                    (Vistas Blade - 87 archivos)
    ├── layouts/
    │   └── app.blade.php     (Layout principal)
    ├── auth/
    │   ├── login.blade.php
    │   └── passwords/
    ├── admin/
    │   ├── dashboard.blade.php
    │   ├── vehiculos/
    │   ├── usuarios/
    │   ├── productos/
    │   ├── tipos_producto/
    │   ├── configuracion/
    │   ├── reportes/         (10 archivos PDF)
    │   └── asistencia/
    ├── control/
    │   ├── salidas/          (4 archivos)
    │   ├── produccion/       (4 archivos)
    │   ├── mantenimiento/    (3 archivos)
    │   ├── insumos/          (3 archivos)
    │   ├── fumigacion/       (3 archivos)
    │   ├── fosa-septica/     (3 archivos)
    │   ├── tanques/          (4 archivos)
    │   ├── asistencia-semanal/ (5 archivos)
    │   └── empleados/        (3 archivos)
    ├── inventario/
    │   ├── dashboard.blade.php
    │   ├── index.blade.php
    │   ├── productos/
    │   ├── pdf/
    │   └── alertas/
    ├── personal/
    │   └── asistencia/       (2 archivos)
    ├── produccion/           (Sistema antiguo - 5 archivos)
    └── errors/
        ├── 403.blade.php
        ├── 404.blade.php
        └── 500.blade.php
```

---

## 🎨 FRONTEND (CSS/JS)

### resources/css/app.css
```css
@import 'tailwindcss';

/* Estilos personalizados del proyecto */
```

**Compilado a**: `public/build/assets/app-*.css`

### resources/js/app.js
```javascript
import './bootstrap';

// JavaScript principal de la aplicación
```

**Compilado a**: `public/build/assets/app-*.js`

---

## 📄 VISTAS BLADE (87 archivos)

### 1. Layout Principal

#### layouts/app.blade.php
**Propósito**: Template base para todas las páginas

**Estructura**:
```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Agua Colegial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Navbar -->
    <nav>...</nav>

    <!-- Sidebar -->
    <aside>...</aside>

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
```

**Secciones**:
- `@yield('title')`: Título de página
- `@yield('content')`: Contenido principal
- `@stack('scripts')`: Scripts adicionales

---

### 2. Autenticación (auth/)

#### login.blade.php
**Ruta**: `GET /login`
**Propósito**: Formulario de inicio de sesión

**Elementos**:
- Campo email (required)
- Campo password (required, min: 6)
- Checkbox "Recordarme"
- Botón "Iniciar Sesión"
- Enlace "¿Olvidaste tu contraseña?"

**Rate Limiting**: 5 intentos por minuto

#### passwords/email.blade.php
**Ruta**: `GET /password/reset`
**Propósito**: Solicitar reset de contraseña

---

### 3. Dashboard Administrativo (admin/)

#### dashboard.blade.php
**Ruta**: `GET /admin/dashboard`
**Middleware**: `role:admin`

**Secciones**:
1. **KPI Cards** (4 principales):
   - Producción del mes
   - Stock total
   - Entradas del mes
   - Salidas del mes

2. **Gráficos**:
   - Producción últimos 7 días
   - Movimientos de inventario

3. **Listas**:
   - Últimos 8 movimientos
   - Últimas 5 salidas
   - Próximos 5 mantenimientos

4. **Estadísticas de Módulos**:
   - Total salidas
   - Total producción
   - Total mantenimientos
   - Total fumigaciones
   - Total fosa séptica
   - Total tanques
   - Total insumos
   - Total asistencias

**Actualización en Tiempo Real**: AJAX cada 30 segundos

---

### 4. Módulos Administrativos (admin/)

#### vehiculos/ (3 archivos)
- `index.blade.php`: Lista de vehículos
- `create.blade.php`: Registrar vehículo
- `edit.blade.php`: Editar vehículo

**Campos**:
- Placa, marca, modelo, año, tipo, estado, responsable, observaciones

#### usuarios/ (3 archivos)
- `index.blade.php`: Gestión de usuarios
- `create.blade.php`: Crear usuario
- `edit.blade.php`: Editar usuario

**Campos**:
- Nombre, email, password, rol, estado

#### productos/ (3 archivos)
- `index.blade.php`: Catálogo de productos
- `create.blade.php`: Nuevo producto
- `edit.blade.php`: Editar producto

**Campos**:
- Código, nombre, descripción, tipo, unidad medida, precio, stock mínimo, imagen

#### tipos_producto/ (3 archivos)
- Gestión de categorías de productos

#### configuracion/
- `index.blade.php`: Panel de configuración del sistema
  - Backups manuales
  - Descargar backups
  - Limpiar cache
  - Ver configuración

#### asistencia/ (2 archivos)
- `index.blade.php`: Asistencias de todo el personal
- `ver_personal.blade.php`: Asistencias por empleado

---

### 5. Reportes (admin/reportes/) - 10 archivos PDF

#### Vistas HTML
- `index.blade.php`: Selector de reportes
- `produccion.blade.php`: Reporte de producción (HTML)
- `inventario.blade.php`: Reporte de inventario (HTML)

#### Vistas PDF (9 archivos)
1. `produccion_pdf.blade.php`: Producción diaria
2. `inventario_pdf.blade.php`: Movimientos inventario
3. `salidas_pdf.blade.php`: Despachos
4. `mantenimiento_pdf.blade.php`: Mantenimientos
5. `fumigacion_pdf.blade.php`: Control fumigación
6. `fosa_septica_pdf.blade.php`: Limpieza fosa séptica
7. `tanques_pdf.blade.php`: Limpieza tanques
8. `insumos_pdf.blade.php`: Control insumos
9. `asistencia_pdf.blade.php`: Asistencia personal

**Generación**: Usando `barryvdh/laravel-dompdf`

---

### 6. Módulos de Control (control/)

#### salidas/ (4 archivos)
- `index.blade.php`: Lista de salidas de productos
- `create.blade.php`: Registrar nueva salida
- `edit.blade.php`: Editar salida
- `show.blade.php`: Ver detalle de salida

**Funcionalidades**:
- Registrar salidas por producto
- Calcular retornos
- Asignar vehículo y chofer
- Generar PDF de salida

#### produccion/ (4 archivos)
- `index.blade.php`: Registros de producción diaria
- `create.blade.php`: Nueva producción
- `edit.blade.php`: Editar producción
- `show.blade.php`: Ver detalle

**Campos principales**:
- Fecha, turno, supervisor
- Productos producidos (botellones, bolsas, botellas)
- Control de calidad (cloro, pH, turbidez)
- Materiales usados
- Observaciones

#### mantenimiento/ (3 archivos)
- `index.blade.php`: Mantenimientos de equipos
- `create.blade.php`: Programar mantenimiento
- `edit.blade.php`: Actualizar mantenimiento

#### insumos/ (3 archivos)
- Gestión de insumos y materias primas

#### fumigacion/ (3 archivos)
- Control de fumigación (BPM)

#### fosa-septica/ (3 archivos)
- Control limpieza fosa séptica (BPM)

#### tanques/ (4 archivos)
- Control limpieza y desinfección tanques (BPM)

#### empleados/ (3 archivos)
- `create.blade.php`: Registrar empleado
- `edit.blade.php`: Actualizar empleado
- `show.blade.php`: Ver perfil empleado

**Campos**:
- Datos personales (nombre, CI, dirección, teléfono)
- Datos laborales (cargo, fecha ingreso, salario)
- Documentos (foto, garantía)

#### asistencia-semanal/ (5 archivos)
- `index.blade.php`: Tabla de asistencias
- `create.blade.php`: Registrar asistencia
- `edit.blade.php`: Editar asistencia
- `registro-rapido.blade.php`: Registro rápido entrada/salida
- `mi-registro.blade.php`: Vista para personal (marcar propia asistencia)

---

### 7. Inventario (inventario/)

#### dashboard.blade.php
**Ruta**: `GET /inventario/dashboard`
**Contenido**:
- Stock por producto
- Alertas de stock bajo
- Gráfico de movimientos

#### index.blade.php
**Lista de productos con stock actual**

#### create_movimiento.blade.php
**Registrar movimiento manual** (entrada/salida)

#### movimiento_historial.blade.php
**Historial de todos los movimientos**

#### historial.blade.php
**Historial por producto específico**

#### alertas.blade.php
**Alertas de stock bajo**:
- Productos con stock < umbral
- Botones: Atender / Ignorar

#### productos/ (2 archivos)
- `create.blade.php`: Crear producto
- `edit.blade.php`: Editar producto

#### pdf/
- `movimientos.blade.php`: PDF de movimientos

---

### 8. Asistencia Personal (personal/asistencia/)

#### index.blade.php
**Ruta**: `GET /mi-asistencia`
**Propósito**: Panel personal para marcar asistencia

**Funcionalidades**:
- Marcar entrada
- Marcar salida
- Registrar ausencia/permiso
- Ver mi asistencia de hoy

#### historial.blade.php
**Historial de asistencias del usuario actual**

---

### 9. Producción Antiguo (produccion/) - DESHABILITADO

**5 archivos**:
- dashboard.blade.php
- index.blade.php
- create.blade.php
- show.blade.php
- almacen/index.blade.php

**Estado**: Sistema antiguo reemplazado por `/control/produccion`
**Rutas**: Comentadas en web.php

---

### 10. Errores (errors/)

#### 403.blade.php
**HTTP 403 Forbidden**
- "No tienes permisos para acceder a este recurso"
- Botón: Volver al dashboard

#### 404.blade.php
**HTTP 404 Not Found**
- "Página no encontrada"
- Botón: Volver al inicio

#### 500.blade.php
**HTTP 500 Internal Server Error**
- "Error interno del servidor"
- En producción: Mensaje genérico
- En desarrollo: Stack trace

---

## 🎨 COMPONENTES BLADE REUTILIZABLES

### Alertas
```blade
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

### Tablas
```blade
<table class="table">
    <thead>
        <tr>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>...</tr>
        @empty
            <tr>
                <td colspan="10">No hay registros</td>
            </tr>
        @endforelse
    </tbody>
</table>
```

### Paginación
```blade
{{ $items->links() }}
```

### Formularios
```blade
<form method="POST" action="{{ route('control.salidas.store') }}">
    @csrf

    <div class="form-group">
        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha"
               value="{{ old('fecha') }}" required>
        @error('fecha')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit">Guardar</button>
</form>
```

---

## 📊 ESTADÍSTICAS DE VISTAS

| Módulo | Archivos | Descripción |
|--------|----------|-------------|
| Layouts | 1 | Template principal |
| Auth | 2 | Login y reset password |
| Admin Dashboard | 1 | Dashboard principal |
| Admin Módulos | 15 | Vehículos, usuarios, productos, etc. |
| Reportes PDF | 10 | 9 PDFs + índice |
| Control Salidas | 4 | Despachos |
| Control Producción | 4 | Producción diaria |
| Control Mantenimiento | 3 | Mantenimientos |
| Control Insumos | 3 | Insumos |
| Control Fumigación | 3 | BPM fumigación |
| Control Fosa Séptica | 3 | BPM fosa |
| Control Tanques | 4 | BPM tanques |
| Control Empleados | 3 | Personal |
| Control Asistencia | 5 | Asistencia semanal |
| Inventario | 8 | Stock y movimientos |
| Personal | 2 | Asistencia personal |
| Producción Antiguo | 5 | Sistema deshabilitado |
| Errores | 3 | 403, 404, 500 |
| **TOTAL** | **87** | **Archivos Blade** |

---

## 🚀 COMPILACIÓN DE ASSETS

### Desarrollo
```bash
npm run dev
# Inicia Vite dev server
# Hot Module Replacement (HMR)
# URL: http://localhost:5173
```

### Producción
```bash
npm run build
# Compila y minifica assets
# Genera: public/build/assets/
# Hash de archivos para cache busting
```

### Uso en Blade
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**En desarrollo**: Carga desde Vite dev server
**En producción**: Carga archivos compilados de `/build/`

---

## 🎨 TAILWINDCSS v4

### Configuración
```css
/* resources/css/app.css */
@import 'tailwindcss';

@theme {
  /* Personalización de tema */
}
```

### Clases Usadas (Ejemplos)
```blade
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Título</h1>
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Botón
    </button>
</div>
```

---

## ⚠️ NOTAS IMPORTANTES

### 1. Sistema de Producción Duplicado
- **Antiguo**: `resources/views/produccion/` (DESHABILITADO)
- **Nuevo**: `resources/views/control/produccion/` (ACTIVO)

### 2. Vistas PDF
- Usan diseño específico para DomPDF
- NO usar Tailwind en PDFs (compatibilidad limitada)
- Usar estilos inline o `<style>` tag

### 3. Directivas Blade Importantes
```blade
@auth          // Solo usuarios autenticados
@guest         // Solo invitados
@can('admin')  // Verificar permiso
@role('admin') // Verificar rol (custom)
@csrf          // Token CSRF (SIEMPRE en forms POST)
@method('PUT') // Method spoofing
```

### 4. Asset Versionado
```blade
<!-- INCORRECTO: -->
<link rel="stylesheet" href="/css/app.css">

<!-- CORRECTO: -->
@vite(['resources/css/app.css'])
```

### 5. Variables de JavaScript desde Blade
```blade
<script>
    window.config = {
        appUrl: "{{ config('app.url') }}",
        locale: "{{ app()->getLocale() }}"
    };
</script>
```

---

## 🔐 SEGURIDAD EN VISTAS

### XSS Prevention
```blade
<!-- Escapado automático: -->
{{ $variable }}

<!-- Sin escapar (PELIGROSO): -->
{!! $html !!}

<!-- Escapar en JavaScript: -->
<script>
    var data = @json($data);
</script>
```

### CSRF Protection
```blade
<form method="POST">
    @csrf  <!-- Genera: <input type="hidden" name="_token" value="..."> -->
</form>
```

### Autorización en Vistas
```blade
@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">Editar</a>
@endcan

@role('admin')
    <a href="{{ route('admin.dashboard') }}">Admin</a>
@endrole
```

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Archivo**: 19-Resources-Views.md
**Estado**: Carpeta resources/ con 87 vistas Blade documentadas

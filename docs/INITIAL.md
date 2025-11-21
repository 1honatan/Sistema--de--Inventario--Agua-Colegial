# Sistema de Gestión Integral - Agua Colegial

## Información del Proyecto

**Nombre**: Sistema de Gestión Agua Colegial
**Versión**: 1.0
**Fecha de Inicio**: Octubre 2025
**Última Actualización**: Noviembre 2025
**Estado**: Producción

---

## Descripción General

Sistema web integral desarrollado en Laravel para la gestión completa de operaciones de la planta embotelladora Agua Colegial. Permite controlar producción, inventario, personal, vehículos, y todos los procesos operativos diarios.

---

## Tecnologías Utilizadas

### Backend
- **PHP 8.1+**
- **Laravel 10.x** - Framework principal
- **MySQL 5.7+** - Base de datos
- **Eloquent ORM** - Mapeo objeto-relacional

### Frontend
- **Blade** - Motor de plantillas
- **Tailwind CSS 3.x** - Framework CSS
- **Bootstrap 5** - Componentes UI
- **jQuery 3.x** - Manipulación DOM
- **Chart.js** - Gráficos
- **DataTables** - Tablas interactivas
- **SweetAlert2** - Alertas
- **Font Awesome 6** - Iconos

### Autenticación y Seguridad
- **Laravel Breeze** - Sistema de autenticación
- **Spatie Permission** - Roles y permisos

### Generación de Documentos
- **DomPDF** - Generación de PDFs

### Herramientas de Desarrollo
- **Composer** - Dependencias PHP
- **NPM** - Dependencias JS
- **Vite** - Compilación de assets
- **Git** - Control de versiones

---

## Módulos del Sistema

### 1. Autenticación y Seguridad
- Login/Logout de usuarios
- Gestión de roles (Admin, Operador, etc.)
- Control de permisos por módulo
- Sesiones seguras con timeout

### 2. Dashboard Principal
- Tarjetas de estadísticas en tiempo real:
  - Producción del día
  - Stock en inventario
  - Total de salidas
  - Personal activo
- Gráficos de producción por período
- Lista de últimas salidas
- Alertas de productos con stock bajo

### 3. Registro del Personal
- CRUD completo de empleados
- Campos: nombre, cédula, cargo, teléfono, email, fecha ingreso
- Estados: activo/inactivo
- Asignación a vehículos

### 4. Inventario General
- Control de stock en tiempo real
- Movimientos de entrada y salida
- Actualización automática desde producción y salidas
- Vista de stock actual por producto
- Alertas de stock bajo
- Historial de movimientos

### 5. Productos Producidos Diarios
- Registro de producción por fecha
- Múltiples productos por registro
- Control de materiales utilizados
- Actualización automática del inventario (entradas)
- Observaciones del proceso

### 6. Salidas de Productos Diarios
- Registro de despachos diarios
- Asignación de distribuidor/responsable
- Selección de vehículo (autocompletado)
- Productos despachados:
  - Botellones de 20 litros
  - Bolo Grande
  - Bolo Pequeño
  - Gelatina
  - Agua Saborizada
  - Agua de Limón
  - Agua Natural
  - Bolsa de Hielo
  - Dispenser
- Control de retornos por producto
- Registro de chorreados
- Hora de llegada
- Validación de stock disponible
- Actualización automática del inventario

### 7. Control de Insumos
- Registro de recepción de insumos
- Campos: producto, cantidad, unidad, lote, vencimiento
- Control de proveedores
- Responsable de recepción
- Seguimiento de fechas de vencimiento

### 8. Mantenimiento de Equipos
- Registro de mantenimientos preventivos/correctivos
- Selección múltiple de equipos
- Descripción del trabajo realizado
- Técnico responsable
- Costo del mantenimiento
- Programación de próximo mantenimiento
- Alertas de mantenimientos vencidos

### 9. Limpieza de Tanques de Agua
- Registro por tanque
- Capacidad en litros
- Procedimiento de limpieza
- Productos de desinfección utilizados
- Responsable y supervisor
- Programación de próxima limpieza

### 10. Limpieza de Fosa Séptica
- Registro de limpiezas
- Tipo de fosa
- Detalle del trabajo
- Empresa contratada
- Programación de próxima limpieza

### 11. Control de Fumigación
- Registro de fumigaciones
- Área fumigada
- Producto utilizado y cantidad
- Empresa fumigadora
- Responsable
- Programación de próxima fumigación

### 12. Gestión de Vehículos
- CRUD de vehículos
- Campos: placa, marca, modelo, año, color, tipo
- Asignación de responsable/conductor
- Estados: activo/inactivo/en mantenimiento

### 13. Reportes PDF
Generación de reportes en formato PDF para:
- **Salidas de Productos**: Despachos por período
- **Producción Diaria**: Productos fabricados
- **Mantenimiento**: Historial de equipos
- **Fumigación**: Registro de fumigaciones
- **Fosa Séptica**: Limpiezas realizadas
- **Tanques de Agua**: Control de limpieza
- **Insumos**: Recepción de materiales
- **Asistencia**: Control de personal
- **Inventario**: Stock y movimientos

Cada reporte incluye:
- Encabezado con logo y nombre del sistema
- Período seleccionado
- Tabla de datos detallados
- Resumen con totales y estadísticas
- Fecha y hora de generación

### 14. Administración
- Gestión de roles de usuario
- Asignación de permisos
- Configuración del sistema

---

## Estructura de Base de Datos

### Tablas Principales

#### Usuarios y Autenticación
- `users` - Usuarios del sistema
- `roles` - Roles disponibles
- `permissions` - Permisos del sistema
- `model_has_roles` - Relación usuario-rol
- `role_has_permissions` - Relación rol-permiso

#### Personal
- `personal` - Datos de empleados

#### Inventario
- `productos` - Catálogo de productos
- `inventarios` - Movimientos de stock
- `v_stock_actual` - Vista de stock actual

#### Producción
- `control_produccion_diaria` - Registro diario
- `control_produccion_productos` - Productos por producción
- `control_produccion_materiales` - Materiales usados

#### Salidas
- `control_salidas_productos` - Despachos diarios

#### Controles
- `control_insumos` - Recepción de insumos
- `control_mantenimiento_equipos` - Mantenimientos
- `control_tanques_agua` - Limpieza tanques
- `control_fosa_septica` - Limpieza fosa
- `control_fumigacion` - Fumigaciones

#### Gestión
- `vehiculos` - Flota de vehículos
- `historial_reportes` - Reportes generados

---

## Estructura de Archivos

```
agua_colegial/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ReporteController.php
│   │   │   │   └── RolController.php
│   │   │   ├── Control/
│   │   │   │   ├── ProduccionDiariaController.php
│   │   │   │   ├── SalidasController.php
│   │   │   │   ├── InsumoController.php
│   │   │   │   ├── MantenimientoController.php
│   │   │   │   ├── TanqueAguaController.php
│   │   │   │   ├── FosaSepticaController.php
│   │   │   │   └── FumigacionController.php
│   │   │   ├── PersonalController.php
│   │   │   ├── InventarioController.php
│   │   │   └── VehiculoController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Personal.php
│       ├── Producto.php
│       ├── Inventario.php
│       ├── Vehiculo.php
│       └── Control/
│           ├── ProduccionDiaria.php
│           ├── SalidaProducto.php
│           ├── Insumo.php
│           ├── MantenimientoEquipo.php
│           ├── TanqueAgua.php
│           ├── FosaSeptica.php
│           └── Fumigacion.php
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── assets/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   └── reportes/
│   │   ├── control/
│   │   │   ├── produccion/
│   │   │   ├── salidas/
│   │   │   ├── insumos/
│   │   │   ├── mantenimiento/
│   │   │   ├── tanques/
│   │   │   ├── fosa-septica/
│   │   │   └── fumigacion/
│   │   ├── personal/
│   │   ├── inventario/
│   │   └── vehiculos/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── storage/
└── docs/
```

---

## Rutas Principales

### Autenticación
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `POST /logout` - Cerrar sesión

### Dashboard
- `GET /admin/dashboard` - Panel principal

### Personal
- `GET /personal` - Lista de personal
- `GET /personal/create` - Formulario nuevo
- `POST /personal` - Guardar nuevo
- `GET /personal/{id}/edit` - Formulario editar
- `PUT /personal/{id}` - Actualizar
- `DELETE /personal/{id}` - Eliminar

### Inventario
- `GET /inventario` - Lista de inventario
- `GET /inventario/create` - Nuevo movimiento
- `POST /inventario` - Guardar movimiento

### Producción
- `GET /control/produccion` - Lista de producciones
- `GET /control/produccion/create` - Nueva producción
- `POST /control/produccion` - Guardar producción

### Salidas
- `GET /control/salidas` - Lista de salidas
- `GET /control/salidas/create` - Nueva salida
- `POST /control/salidas` - Guardar salida

### Controles
- `/control/insumos` - Control de insumos
- `/control/mantenimiento` - Mantenimiento equipos
- `/control/tanques` - Limpieza tanques
- `/control/fosa-septica` - Limpieza fosa
- `/control/fumigacion` - Control fumigación

### Vehículos
- `GET /admin/vehiculos` - Gestión de vehículos

### Reportes
- `GET /admin/reportes` - Lista de reportes
- `GET /admin/reportes/salidas` - Reporte salidas PDF
- `GET /admin/reportes/produccion` - Reporte producción PDF
- `GET /admin/reportes/mantenimiento` - Reporte mantenimiento PDF
- `GET /admin/reportes/fumigacion` - Reporte fumigación PDF
- `GET /admin/reportes/fosa-septica` - Reporte fosa PDF
- `GET /admin/reportes/tanques` - Reporte tanques PDF
- `GET /admin/reportes/insumos` - Reporte insumos PDF
- `GET /admin/reportes/asistencia` - Reporte asistencia PDF
- `GET /admin/reportes/inventario` - Reporte inventario PDF

---

## Configuración del Sistema

### Variables de Entorno (.env)
```env
APP_NAME="Agua Colegial"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost/agua_colegial/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=agua_colegial_bd
DB_USERNAME=root
DB_PASSWORD=
```

### Credenciales por Defecto
- **Email**: admin@aguacolegial.com
- **Contraseña**: password

---

## Características Implementadas

### Seguridad
- Autenticación de usuarios
- Control de acceso por roles
- Validación de formularios
- Protección CSRF
- Sanitización de datos

### Rendimiento
- Vistas cacheadas
- Consultas optimizadas
- Paginación de resultados
- Lazy loading de relaciones

### UX/UI
- Diseño responsivo (móvil/tablet/desktop)
- Interfaz intuitiva y moderna
- Alertas visuales y confirmaciones
- Autocompletado de campos
- Validación en tiempo real

### Automatización
- Actualización automática de inventario
- Cálculo automático de totales
- Generación automática de referencias
- Autocompletado de vehículo por responsable

---

## Flujo de Trabajo del Sistema

### Producción
1. Registrar producción diaria
2. Sistema suma al inventario automáticamente
3. Stock actualizado en tiempo real

### Salidas/Despachos
1. Seleccionar distribuidor
2. Sistema autocompleta vehículo
3. Ingresar productos a despachar
4. Sistema valida stock disponible
5. Ingresar retornos
6. Sistema actualiza inventario (resta salidas, suma retornos)

### Reportes
1. Seleccionar tipo de reporte
2. Definir período (fecha inicio/fin)
3. Generar PDF
4. Descargar documento

---

## Mejoras Implementadas (Noviembre 2025)

1. **Reportes PDF corregidos**
   - Campos correctos en fumigación, fosa séptica, tanques, insumos
   - Totales y estadísticas mejorados

2. **Dashboard optimizado**
   - Eliminada sección de mantenimientos pendientes
   - Últimas salidas a ancho completo

3. **Inventario mejorado**
   - Eliminado botón Excel no funcional
   - Solo generación de PDF

4. **Salidas de productos**
   - Validación de stock antes de guardar
   - Autocompletado de vehículo por responsable

5. **Documentación actualizada**
   - Manual de funcionamiento completo
   - Documentación técnica organizada

---

## Documentación Disponible

- `docs/README.md` - Descripción general
- `docs/INSTALACION.md` - Guía de instalación
- `docs/HERRAMIENTAS.md` - Stack tecnológico
- `docs/ESTRUCTURA_BD.md` - Base de datos
- `docs/SESION.md` - Resumen de desarrollo
- `docs/INITIAL.md` - Este documento
- `sistema_funcionamiento.md` - Manual de usuario

---

## Contacto y Soporte

**Sistema desarrollado para**: Agua Colegial
**Año**: 2025
**Versión**: 1.0

---

*Documento generado: Noviembre 2025*

# 📘 DOCUMENTACIÓN COMPLETA - SISTEMA AGUA COLEGIAL

## 📋 ÍNDICE
1. [Información General](#información-general)
2. [Paleta de Colores](#paleta-de-colores)
3. [Acceso al Sistema](#acceso-al-sistema)
4. [Panel de Administración](#panel-de-administración)
5. [Módulos del Sistema](#módulos-del-sistema)
6. [Flujo Completo del Sistema](#flujo-completo-del-sistema)

---

## 🔷 INFORMACIÓN GENERAL

**Nombre del Sistema:** Agua Colegial - Sistema de Gestión de Producción de Agua
**Versión de Laravel:** 12.35.0
**PHP:** 8.2.12
**Base de Datos:** MariaDB 10.4.32
**Puerto MySQL:** 3307
**URL del Sistema:** http://127.0.0.1:8000

---

## 🎨 PALETA DE COLORES OFICIAL

El sistema utiliza una paleta de colores **azul/cian/teal** unificada:

```css
/* Colores Principales */
--primary: #0ea5e9 (Sky Blue)
--secondary: #06b6d4 (Cyan)
--tertiary: #14b8a6 (Teal)
--dark: #0c4a6e (Dark Blue)

/* Gradientes de Fondo */
background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);

/* Colores de Texto */
--text-info: #0ea5e9
--text-primary: #0c4a6e
--text-cyan: #06b6d4
```

**IMPORTANTE:** NO se utilizan colores naranjas ni verdes en el sistema.

---

## 🔐 ACCESO AL SISTEMA

### PASO 1: Iniciar Sesión
1. Abrir navegador web
2. Ir a: `http://127.0.0.1:8000/login`
3. Ingresar credenciales de administrador:
   - **Usuario:** (definido en base de datos)
   - **Contraseña:** (definida en base de datos)
4. Click en "Iniciar Sesión"

### PASO 2: Pantalla Inicial
Después del login, el sistema redirige a la página principal (raíz `/`)

---

## 👨‍💼 PANEL DE ADMINISTRACIÓN

URL: `http://127.0.0.1:8000/admin/dashboard`

### Módulos Disponibles en Admin:

#### 1. **Dashboard Administrativo**
- **Ruta:** `/admin/dashboard`
- **Función:** Vista general del sistema
- **Controlador:** `Admin\DashboardController@index`

#### 2. **Gestión de Productos**
- **Listar productos:** `/admin/productos`
- **Crear producto:** `/admin/productos/create`
- **Editar producto:** `/admin/productos/{id}/edit`
- **Eliminar producto:** DELETE `/admin/productos/{id}`
- **Controlador:** `Admin\ProductoController`

**Flujo de creación de producto:**
1. Click en "Nuevo Producto"
2. Completar formulario:
   - Nombre del producto
   - Tipo de producto (seleccionar de lista)
   - Código/SKU
   - Descripción
3. Guardar
4. El producto aparece en la lista de productos

#### 3. **Tipos de Producto**
- **Listar tipos:** `/admin/tipos-producto`
- **Crear tipo:** `/admin/tipos-producto/create`
- **Editar tipo:** `/admin/tipos-producto/{id}/edit`
- **Activar/Desactivar:** POST `/admin/tipos-producto/{id}/activar`
- **Controlador:** `Admin\TipoProductoController`

#### 4. **Gestión de Vehículos**
- **Listar vehículos:** `/admin/vehiculos`
- **Crear vehículo:** `/admin/vehiculos/create`
- **Editar vehículo:** `/admin/vehiculos/{id}/edit`
- **Cambiar estado:** POST `/admin/vehiculos/{id}/toggle-estado`
- **Controlador:** `Admin\VehiculoController`

**Datos de vehículo:**
- Placa
- Marca
- Modelo
- Año
- Estado (activo/inactivo)

#### 5. **Roles y Permisos**
- **Listar roles:** `/admin/roles`
- **Crear rol:** `/admin/roles/create`
- **Editar rol:** `/admin/roles/{id}/edit`
- **Controlador:** `Admin\RolController`

#### 6. **Asistencia (Vista Admin)**
- **Ver todas las asistencias:** `/admin/asistencia`
- **Ver asistencia por personal:** `/admin/asistencia/personal/{id}`
- **Generar reporte:** `/admin/asistencia/reporte`
- **Controlador:** `Admin\AsistenciaController`

#### 7. **Reportes Administrativos**
- **Página de reportes:** `/admin/reportes`
- **Reporte de inventario:** `/admin/reportes/inventario`
- **Reporte de inventario PDF:** `/admin/reportes/inventario/pdf`
- **Reporte de producción:** `/admin/reportes/produccion`
- **Reporte de producción PDF:** `/admin/reportes/produccion/pdf`
- **Controlador:** `Admin\ReporteController`

#### 8. **Configuración del Sistema**
- **Página de configuración:** `/admin/configuracion`
- **Generar backup:** POST `/admin/configuracion/backup`
- **Descargar backup:** `/admin/configuracion/backup/{archivo}/descargar`
- **Eliminar backup:** DELETE `/admin/configuracion/backup/{archivo}`
- **Controlador:** `Admin\ConfiguracionController`

---

## 📦 MÓDULOS DEL SISTEMA

### A. MÓDULO DE PRODUCCIÓN

#### Dashboard de Producción
- **URL:** `/produccion/dashboard`
- **Función:** Vista general de producción
- **Controlador:** `Produccion\DashboardProduccionController`

#### Registro de Producción
- **Listar producciones:** `/produccion`
- **Crear producción:** `/produccion/crear`
- **Ver detalle:** `/produccion/{id}`
- **Generar reporte:** `/produccion/reporte/generar`
- **Controlador:** `Produccion\ProduccionController`

**Flujo de registro de producción:**
1. Ir a `/produccion/crear`
2. Completar datos:
   - Fecha de producción
   - Turno
   - Tipo de producto
   - Cantidad producida
   - Personal a cargo
3. Guardar
4. Ver en lista de producciones

#### Almacén
- **Ver inventario:** `/almacen`
- **Crear producto en almacén:** `/almacen/crear`
- **Editar producto:** `/almacen/{id}/editar`
- **Ajustar stock:** `/almacen/{id}/ajustar-stock`
- **Procesar ajuste:** POST `/almacen/{id}/procesar-ajuste`
- **Eliminar:** DELETE `/almacen/{id}`
- **Controlador:** `Produccion\AlmacenController`

---

### B. MÓDULO DE INVENTARIO

#### Dashboard de Inventario
- **URL:** `/inventario/dashboard`
- **Controlador:** `Inventario\DashboardInventarioController`

#### Gestión de Inventario
- **Ver inventario:** `/inventario`
- **Ver historial de producto:** `/inventario/producto/{id}/historial`
- **Controlador:** `Inventario\InventarioController`

#### Movimientos de Inventario
- **Crear movimiento:** `/inventario/movimiento/crear`
- **Guardar movimiento:** POST `/inventario/movimiento`
- **Historial de movimientos:** `/inventario/movimiento/historial`
- **Exportar a Excel:** POST `/inventario/movimiento/exportar-excel`
- **Exportar a PDF:** POST `/inventario/movimiento/exportar-pdf`

**Tipos de movimientos:**
- Entrada (compra, devolución, ajuste positivo)
- Salida (venta, consumo, ajuste negativo)

#### Alertas de Inventario
- **Ver alertas:** `/inventario/alertas`
- **Atender alerta:** POST `/inventario/alertas/{id}/atender`
- **Ignorar alerta:** POST `/inventario/alertas/{id}/ignorar`
- **Verificar alertas (API):** `/inventario/api/verificar-alertas`

---

### C. MÓDULO DE CONTROL

#### 1. Control de Producción Diaria
- **Listar registros:** `/control/produccion`
- **Crear registro:** `/control/produccion/crear`
- **Ver detalle:** `/control/produccion/{id}`
- **Editar:** `/control/produccion/{id}/editar`
- **Eliminar:** DELETE `/control/produccion/{id}`
- **Controlador:** `Control\ProduccionDiariaController`

**Datos registrados:**
- Fecha
- Turno
- Producto
- Cantidad producida
- Observaciones

#### 2. Control de Salidas de Productos
- **Listar salidas:** `/control/salidas`
- **Crear salida:** `/control/salidas/crear`
- **Ver detalle:** `/control/salidas/{id}`
- **Editar:** `/control/salidas/{id}/editar`
- **Generar PDF:** `/control/salidas/{id}/pdf`
- **Eliminar:** DELETE `/control/salidas/{id}`
- **Controlador:** `Control\SalidasController`

**Proceso de salida:**
1. Ir a `/control/salidas/crear`
2. Completar:
   - Fecha de salida
   - Vehículo (seleccionar de lista)
   - Productos y cantidades
   - Destino
   - Responsable
3. Guardar
4. Se puede generar PDF de la salida

#### 3. Control de Mantenimiento de Equipos
- **Listar mantenimientos:** `/control/mantenimiento`
- **Crear mantenimiento:** `/control/mantenimiento/crear`
- **Ver detalle:** `/control/mantenimiento/{id}`
- **Editar:** `/control/mantenimiento/{id}/editar`
- **Eliminar:** DELETE `/control/mantenimiento/{id}`
- **Controlador:** `Control\MantenimientoController`

**Datos de mantenimiento:**
- Fecha
- Equipos mantenidos (se puede seleccionar múltiples):
  - Máquina de Agua Natural
  - Máquina de Limón y Sabor
  - Máquina de Bolos
  - Máquina de Hielo
  - Turiles Grandes
  - Turiles Medianos
  - Máquina Limpiadora de Botellones 20L
- Personal responsable (seleccionar de lista)
- Supervisado por: Lucia Cruz Farfan (fijo)
- Productos de limpieza utilizados (checkboxes múltiples)

#### 4. Control de Tanques de Agua
- **Listar registros:** `/control/tanques`
- **Crear registro:** `/control/tanques/crear`
- **Editar:** `/control/tanques/{id}/editar`
- **Eliminar:** DELETE `/control/tanques/{id}`
- **Controlador:** `Control\TanquesController`

#### 5. Control de Fumigación
- **Listar registros:** `/control/fumigacion`
- **Crear registro:** `/control/fumigacion/crear`
- **Editar:** `/control/fumigacion/{id}/editar`
- **Eliminar:** DELETE `/control/fumigacion/{id}`
- **Controlador:** `Control\FumigacionController`

#### 6. Control de Insumos
- **Listar insumos:** `/control/insumos`
- **Crear insumo:** `/control/insumos/crear`
- **Editar insumo:** `/control/insumos/{id}/editar`
- **Eliminar:** DELETE `/control/insumos/{id}`
- **Controlador:** `Control\InsumosController`

**Campos del formulario:**
- Fecha (autocompletada con fecha actual)
- Producto del Insumo (ej: Cloro, Detergente, etc.)
- Cantidad
- Unidad de Medida (dropdown):
  - Kilogramos (kg)
  - Gramos (g)
  - Litros (L)
  - Mililitros (ml)
- Número de Lote (opcional)
- Fecha de Vencimiento (opcional)
- Responsable: Lucia Cruz Farfan (fijo, readonly)
- Proveedor (opcional)
- Observaciones (opcional)

**Vista de índice:**
- Tarjetas (cards) con toda la información
- Alertas de vencimiento:
  - 🔴 Rojo: Producto vencido
  - 🟡 Amarillo: Vence en 30 días o menos (muestra días restantes)
  - 🟢 Verde: Vencimiento lejano

#### 7. Limpieza de Fosa Séptica
- **Listar registros:** `/control/fosa-septica`
- **Crear registro:** `/control/fosa-septica/crear`
- **Editar:** `/control/fosa-septica/{id}/editar`
- **Eliminar:** DELETE `/control/fosa-septica/{id}`
- **Controlador:** `Control\FosaSepticaController`

**Campos del formulario:**
- Fecha de Limpieza (autocompletada con fecha actual)
- Tipo de Fosa (identificación): ej: "Fosa Principal", "Fosa #1"
- Próxima Limpieza (autocompleta +5 meses, se recalcula automáticamente)
- Responsable: Lucia Cruz Farfan (fijo, readonly)
- Empresa Contratada: Servicio Master Bolivia SRL (fijo, readonly)
- Detalle del Trabajo (dropdown):
  - Limpieza y Retiro
  - Retiro de Residuos
- Observaciones (opcional)

**Vista de índice:**
- Diseño de tarjetas (cards)
- Muestra tipo de fosa en badge verde
- Fechas con badges específicos
- Toda la información organizada en grid responsivo

#### 8. Gestión de Empleados
- **Crear empleado:** `/control/empleados/crear`
- **Guardar empleado:** POST `/control/empleados`
- **Editar empleado:** `/control/empleados/{id}/editar`
- **Actualizar:** PUT `/control/empleados/{id}`
- **Controlador:** `Control\EmpleadoController`

#### 9. Asistencia Semanal
- **Listar asistencias:** `/control/asistencia-semanal`
- **Crear registro:** `/control/asistencia-semanal/crear`
- **Registro rápido:** `/control/asistencia-semanal/registro-rapido`
- **Editar:** `/control/asistencia-semanal/{id}/editar`
- **Eliminar:** DELETE `/control/asistencia-semanal/{id}`
- **Generar reporte:** `/control/asistencia-semanal/reporte`
- **Controlador:** `Control\AsistenciaSemanalController`

**Funciones especiales:**
- **Registrar entrada:** POST `/control/asistencia-semanal/registrar-entrada`
- **Registrar salida:** POST `/control/asistencia-semanal/registrar-salida`

**Mi Asistencia Semanal (Vista Personal):**
- **Ver mi asistencia:** `/mi-asistencia-semanal`
- **Marcar mi entrada:** POST `/mi-asistencia-semanal/marcar-entrada`
- **Marcar mi salida:** POST `/mi-asistencia-semanal/marcar-salida`

---

## 🔄 FLUJO COMPLETO DEL SISTEMA - PASO A PASO

### INICIO DE SESIÓN Y NAVEGACIÓN PRINCIPAL

**PASO 1: Login**
```
http://127.0.0.1:8000/login
↓
Ingresar credenciales
↓
Click "Iniciar Sesión"
↓
Redirige a página principal "/"
```

**PASO 2: Menú Principal**
El usuario ve el menú de navegación con las siguientes opciones:
- Admin (si tiene permisos)
- Producción
- Inventario
- Control
- Mi Asistencia

---

### FLUJO COMPLETO: ADMINISTRACIÓN

**1. CONFIGURAR PRODUCTOS**
```
/admin/productos
↓
Click "Nuevo Producto"
↓
/admin/productos/create
↓
Completar:
- Nombre
- Tipo de producto
- Código
- Descripción
↓
Click "Guardar"
↓
Producto guardado en BD
↓
Vuelve a /admin/productos (lista actualizada)
```

**2. CONFIGURAR VEHÍCULOS**
```
/admin/vehiculos
↓
Click "Nuevo Vehículo"
↓
/admin/vehiculos/create
↓
Completar:
- Placa
- Marca
- Modelo
- Año
↓
Click "Guardar"
↓
Vehículo registrado
↓
Puede activar/desactivar con toggle
```

**3. GENERAR REPORTES**
```
/admin/reportes
↓
Seleccionar tipo de reporte:
- Reporte de Inventario
- Reporte de Producción
↓
Click en opción deseada
↓
/admin/reportes/inventario o /admin/reportes/produccion
↓
Ver datos en pantalla
↓
Opción: Descargar PDF
↓
/admin/reportes/inventario/pdf o /admin/reportes/produccion/pdf
↓
Se descarga el archivo PDF
```

**4. BACKUPS DEL SISTEMA**
```
/admin/configuracion
↓
Click "Generar Backup"
↓
POST /admin/configuracion/backup
↓
Sistema genera backup de BD
↓
Backup guardado en lista
↓
Opciones:
- Descargar backup
- Eliminar backup
```

---

### FLUJO COMPLETO: PRODUCCIÓN

**1. REGISTRAR PRODUCCIÓN DIARIA**
```
/produccion
↓
Click "Nueva Producción"
↓
/produccion/crear
↓
Completar:
- Fecha
- Turno (mañana/tarde/noche)
- Tipo de producto
- Cantidad producida
- Personal responsable
↓
Click "Guardar"
↓
Registro guardado en BD
↓
Actualiza inventario automáticamente
↓
Vuelve a /produccion (lista actualizada)
```

**2. GESTIÓN DE ALMACÉN**
```
/almacen
↓
Ver productos en inventario
↓
Opciones:
A) Crear nuevo producto
B) Ajustar stock de producto existente

OPCIÓN A:
/almacen/crear
↓
Completar datos del producto
↓
Guardar
↓
Producto añadido a almacén

OPCIÓN B:
Click "Ajustar Stock" en producto
↓
/almacen/{id}/ajustar-stock
↓
Seleccionar tipo de ajuste:
- Entrada
- Salida
↓
Ingresar cantidad y motivo
↓
POST /almacen/{id}/procesar-ajuste
↓
Stock actualizado
```

**3. VER DASHBOARD DE PRODUCCIÓN**
```
/produccion/dashboard
↓
Ver métricas:
- Producción del día
- Producción del mes
- Gráficos de tendencias
- Productos más producidos
```

---

### FLUJO COMPLETO: INVENTARIO

**1. CONSULTAR INVENTARIO**
```
/inventario
↓
Ver lista de todos los productos
↓
Para cada producto se muestra:
- Nombre
- Stock actual
- Stock mínimo
- Estado (alerta si stock bajo)
```

**2. VER HISTORIAL DE MOVIMIENTOS**
```
/inventario/movimiento/historial
↓
Ver todos los movimientos de inventario:
- Fecha
- Tipo (entrada/salida)
- Producto
- Cantidad
- Usuario responsable
- Motivo
↓
Opciones de filtrado:
- Por fecha
- Por tipo de movimiento
- Por producto
↓
Exportar:
- Excel: POST /inventario/movimiento/exportar-excel
- PDF: POST /inventario/movimiento/exportar-pdf
```

**3. REGISTRAR MOVIMIENTO DE INVENTARIO**
```
/inventario/movimiento/crear
↓
Seleccionar tipo de movimiento:
- Entrada (compra, devolución, ajuste +)
- Salida (venta, consumo, ajuste -)
↓
Completar:
- Producto
- Cantidad
- Motivo/Descripción
↓
POST /inventario/movimiento
↓
Movimiento registrado
↓
Stock actualizado automáticamente
↓
Vuelve a /inventario/movimiento/historial
```

**4. GESTIONAR ALERTAS**
```
/inventario/alertas
↓
Ver productos con alertas:
- Stock bajo (menor al mínimo)
- Productos vencidos
- Productos por vencer
↓
Para cada alerta:
OPCIÓN A: Atender
POST /inventario/alertas/{id}/atender
↓
Registrar acción tomada
↓
Alerta marcada como atendida

OPCIÓN B: Ignorar
POST /inventario/alertas/{id}/ignorar
↓
Alerta ignorada temporalmente
```

**5. VER HISTORIAL DE PRODUCTO ESPECÍFICO**
```
/inventario/producto/{id}/historial
↓
Ver todos los movimientos de ese producto:
- Entradas
- Salidas
- Ajustes
- Fechas
- Cantidades
- Stock resultante
```

---

### FLUJO COMPLETO: CONTROL DE PRODUCCIÓN DIARIA

```
/control/produccion
↓
Click "Nueva Producción"
↓
/control/produccion/crear
↓
Completar:
- Fecha
- Turno
- Producto
- Cantidad
- Observaciones
↓
Click "Guardar"
↓
Registro guardado
↓
Vuelve a /control/produccion

Ver detalle:
Click en registro
↓
/control/produccion/{id}
↓
Ver información completa

Editar:
Click "Editar"
↓
/control/produccion/{id}/editar
↓
Modificar datos
↓
PUT /control/produccion/{id}
↓
Registro actualizado
```

---

### FLUJO COMPLETO: CONTROL DE SALIDAS

```
/control/salidas
↓
Click "Nueva Salida"
↓
/control/salidas/crear
↓
Completar:
- Fecha de salida
- Vehículo (seleccionar de dropdown)
- Productos:
  * Agregar productos con cantidades
  * Puede agregar múltiples productos
- Destino
- Responsable de recepción
- Observaciones
↓
Click "Guardar Salida"
↓
POST /control/salidas
↓
Sistema:
1. Guarda registro de salida
2. Descuenta stock del inventario
3. Registra movimiento en historial
↓
Vuelve a /control/salidas

Ver detalle:
Click en salida
↓
/control/salidas/{id}
↓
Ver información completa

Generar PDF:
Click "Generar PDF"
↓
/control/salidas/{id}/pdf
↓
Descarga PDF con:
- Datos de la salida
- Productos y cantidades
- Firma del responsable
- Código QR o barras (opcional)
```

---

### FLUJO COMPLETO: MANTENIMIENTO DE EQUIPOS

```
/control/mantenimiento
↓
Click "Nuevo Mantenimiento"
↓
/control/mantenimiento/crear
↓
Completar:
1. Fecha (autocompleta hoy)
2. Equipos a mantener (checkboxes múltiples):
   ☐ Máquina de Agua Natural
   ☐ Máquina de Limón y Sabor
   ☐ Máquina de Bolos
   ☐ Máquina de Hielo
   ☐ Turiles Grandes
   ☐ Turiles Medianos
   ☐ Máquina Limpiadora de Botellones 20L
   ☐ Otro
3. Próxima Fecha (opcional)
4. Realizado por (seleccionar personal)
5. Supervisado por: Lucia Cruz Farfan (fijo)
6. Productos de limpieza utilizados (checkboxes múltiples)
↓
Click "Guardar Mantenimiento"
↓
POST /control/mantenimiento
↓
Registro guardado en BD
↓
Vuelve a /control/mantenimiento

Ver detalle:
Click en registro
↓
/control/mantenimiento/{id}
↓
Ver información completa del mantenimiento

Editar:
Click "Editar"
↓
/control/mantenimiento/{id}/editar
↓
Modificar datos
↓
PUT /control/mantenimiento/{id}
↓
Actualizado
```

---

### FLUJO COMPLETO: CONTROL DE INSUMOS

```
/control/insumos
↓
Click "Nuevo Insumo"
↓
/control/insumos/crear
↓
Formulario autocompleta:
- Fecha: 14/11/2025 (hoy)
- Responsable: Lucia Cruz Farfan (readonly)

Completar:
1. Producto del Insumo
   (ej: Cloro, Detergente, Desinfectante)
2. Cantidad (número)
3. Unidad de Medida (dropdown):
   - Kilogramos (kg)
   - Gramos (g)
   - Litros (L)
   - Mililitros (ml)
4. Número de Lote (opcional)
5. Fecha de Vencimiento (opcional)
6. Proveedor (opcional)
7. Observaciones (opcional)
↓
Click "Guardar Insumo"
↓
POST /control/insumos
↓
Validaciones:
- fecha: requerida
- producto_insumo: requerido
- cantidad: requerido, numérico, min:0
- unidad_medida: requerido (kg/g/L/ml)
- responsable: requerido
↓
Registro guardado en BD (tabla: control_insumos)
↓
Redirige a /control/insumos

VISTA DE ÍNDICE:
Muestra tarjetas (cards) con:
- Header: Nombre del producto en badge verde
- Botones: Editar (amarillo) | Eliminar (rojo)
- Body:
  * Fecha de registro
  * Cantidad + unidad
  * Número de lote (si existe)
  * Fecha vencimiento con alertas:
    🔴 (Vencido) - fondo rojo
    🟡 (X días) - fondo amarillo si ≤30 días
    🟢 (OK) - fondo verde si >30 días
  * Responsable
  * Proveedor (si existe)
  * Observaciones (si existe)

Editar insumo:
Click "Editar"
↓
/control/insumos/{id}/editar
↓
Formulario precargado con datos
↓
Modificar campos necesarios
↓
Click "Actualizar Insumo"
↓
PUT /control/insumos/{id}
↓
Insumo actualizado
↓
Vuelve a /control/insumos

Eliminar insumo:
Click "Eliminar"
↓
Confirmar: "¿Está seguro de eliminar este insumo?"
↓
DELETE /control/insumos/{id}
↓
Insumo eliminado de BD
↓
Vista actualizada
```

---

### FLUJO COMPLETO: LIMPIEZA DE FOSA SÉPTICA

```
/control/fosa-septica
↓
Click "Nueva Limpieza"
↓
/control/fosa-septica/crear
↓
Formulario autocompleta:
- Fecha Limpieza: 14/11/2025 (hoy)
- Próxima Limpieza: 14/04/2026 (+5 meses)
- Responsable: Lucia Cruz Farfan (readonly)
- Empresa: Servicio Master Bolivia SRL (readonly)

Completar:
1. Tipo de Fosa (identificación)
   Ejemplos: "Fosa Principal", "Fosa #1", "Fosa Sector Norte"
2. Ajustar fechas si es necesario
3. Detalle del Trabajo (dropdown):
   - Limpieza y Retiro
   - Retiro de Residuos
4. Observaciones (opcional)
   Ejemplos: "Fosa con alto nivel de sedimentos", "Requiere reparación"
↓
NOTA: Al cambiar "Fecha de Limpieza", JavaScript automáticamente:
- Recalcula "Próxima Limpieza" (+5 meses)
↓
Click "Guardar Registro"
↓
POST /control/fosa-septica
↓
Validaciones:
- fecha_limpieza: requerida
- tipo_fosa: requerido
- responsable: requerido
- detalle_trabajo: requerido
- empresa_contratada: requerido
- proxima_limpieza: requerida, debe ser posterior a fecha_limpieza
↓
Registro guardado en BD (tabla: control_fosa_septica)
↓
Redirige a /control/fosa-septica

VISTA DE ÍNDICE:
Muestra tarjetas (cards) con:
- Header: Tipo de fosa en badge verde
- Botones: Editar (amarillo) | Eliminar (rojo)
- Body Grid 1:
  * Fecha de limpieza (badge gris)
  * Próxima limpieza (badge amarillo con campana)
  * Tipo de trabajo (badge naranja)
- Body Grid 2:
  * Responsable
  * Empresa contratada
  * Observaciones (si existe)

Editar registro:
Click "Editar"
↓
/control/fosa-septica/{id}/editar
↓
Formulario precargado
↓
Modificar campos
↓
Click "Actualizar Registro"
↓
PUT /control/fosa-septica/{id}
↓
Registro actualizado
↓
Vuelve a /control/fosa-septica

Eliminar registro:
Click "Eliminar"
↓
Confirmar
↓
DELETE /control/fosa-septica/{id}
↓
Eliminado de BD
```

---

### FLUJO COMPLETO: ASISTENCIA SEMANAL

**VISTA ADMINISTRATIVA:**
```
/control/asistencia-semanal
↓
Ver tabla con asistencias:
- Empleado
- Fecha
- Hora entrada
- Hora salida
- Horas trabajadas
- Estado

Opciones:
1. Crear registro manual
2. Registro rápido
3. Generar reporte
```

**OPCIÓN 1: CREAR REGISTRO MANUAL**
```
Click "Crear Registro"
↓
/control/asistencia-semanal/crear
↓
Completar:
- Empleado (seleccionar)
- Fecha
- Hora de entrada
- Hora de salida (opcional)
- Observaciones
↓
POST /control/asistencia-semanal
↓
Registro creado
```

**OPCIÓN 2: REGISTRO RÁPIDO**
```
/control/asistencia-semanal/registro-rapido
↓
Lista de empleados activos
↓
Para cada empleado:
- Botón "Marcar Entrada"
- Botón "Marcar Salida"
↓
Click en botón correspondiente
↓
POST /control/asistencia-semanal/registrar-entrada
o
POST /control/asistencia-semanal/registrar-salida
↓
Hora registrada automáticamente
```

**OPCIÓN 3: GENERAR REPORTE**
```
Click "Generar Reporte"
↓
/control/asistencia-semanal/reporte
↓
Filtrar por:
- Rango de fechas
- Empleado específico (opcional)
↓
Ver reporte:
- Total horas trabajadas
- Días asistidos
- Ausencias
- Llegadas tarde
↓
Opción: Exportar PDF
```

**VISTA PERSONAL (MI ASISTENCIA):**
```
/mi-asistencia-semanal
↓
Ver mi asistencia de la semana
↓
Opciones:
- Marcar mi entrada
- Marcar mi salida
↓
Click "Marcar Entrada"
↓
POST /mi-asistencia-semanal/marcar-entrada
↓
Sistema registra:
- Usuario actual
- Fecha y hora actual
↓
Confirmación mostrada

Click "Marcar Salida"
↓
POST /mi-asistencia-semanal/marcar-salida
↓
Sistema registra hora de salida
↓
Calcula horas trabajadas
```

---

### FLUJO COMPLETO: GESTIÓN DE EMPLEADOS

```
/control/empleados/crear
↓
Completar formulario:
- Nombre completo
- CI (Cédula de Identidad)
- Cargo
- Fecha de ingreso
- Teléfono
- Email
- Dirección
- Estado (activo/inactivo)
↓
POST /control/empleados
↓
Validaciones:
- nombre_completo: requerido
- ci: requerido, único
- cargo: requerido
- fecha_ingreso: requerida
↓
Empleado guardado en BD
↓
Empleado disponible para:
- Asignación a producciones
- Registro de asistencia
- Asignación a mantenimientos

Editar empleado:
/control/empleados/{id}/editar
↓
Modificar datos
↓
PUT /control/empleados/{id}
↓
Actualizado
```

---

## 📊 BASE DE DATOS

### Tablas Principales (29 tablas):

**ADMINISTRACIÓN:**
1. `users` - Usuarios del sistema
2. `roles` - Roles de usuario
3. `tipos_producto` - Tipos de productos
4. `productos` - Catálogo de productos
5. `vehiculos` - Vehículos de la empresa

**INVENTARIO:**
6. `inventario` - Stock de productos
7. `movimientos_inventario` - Historial de movimientos

**PRODUCCIÓN:**
8. `produccion` - Registros de producción
9. `almacen` - Almacén de productos terminados

**CONTROL:**
10. `control_produccion_diaria` - Producción diaria
11. `control_salidas_productos` - Salidas de productos
12. `control_mantenimiento_equipos` - Mantenimientos
13. `control_tanques_agua` - Control de tanques
14. `control_fumigacion` - Fumigaciones
15. `control_insumos` - Insumos (estructura final):
    - id
    - fecha
    - producto_insumo
    - cantidad
    - unidad_medida (ENUM: kg, g, L, ml)
    - numero_lote
    - fecha_vencimiento
    - responsable (default: Lucia Cruz Farfan)
    - proveedor
    - observaciones
    - created_at
    - updated_at
16. `control_fosa_septica` - Limpiezas de fosa (estructura final):
    - id
    - fecha_limpieza
    - tipo_fosa
    - responsable (default: Lucia Cruz Farfan)
    - detalle_trabajo (ENUM: Limpieza y Retiro, Retiro de Residuos)
    - empresa_contratada (default: Servicio Master Bolivia SRL)
    - proxima_limpieza
    - observaciones
    - created_at
    - updated_at

**PERSONAL:**
17. `personal` - Empleados
18. `asistencia_semanal` - Asistencia de personal

**OTRAS:**
19. `migrations` - Migraciones de Laravel
20-29. (Tablas adicionales del sistema)

### Vista:
- `v_stock_actual` - Vista de stock actual en inventario

---

## 🔐 SEGURIDAD

- Sistema de autenticación Laravel
- Protección CSRF en formularios
- Middleware de autenticación en rutas
- Validación de datos en servidor
- Sanitización de entradas

---

## 💾 BACKUPS

**Ubicación:** Se generan desde `/admin/configuracion`
**Función:** Backup completo de base de datos
**Opciones:**
- Generar backup manualmente
- Descargar backups
- Eliminar backups antiguos

---

## 🎨 COMPONENTES MODERNOS

Todos los formularios y vistas utilizan:
- **modern-card**: Tarjetas con sombra y bordes redondeados
- **modern-card-header**: Encabezados con gradiente azul/cian
- **section-box**: Secciones con bordes de colores
- **modern-input**: Inputs estilizados
- **modern-textarea**: Textareas estilizadas
- **btn-modern**: Botones con efectos hover
- **Gradiente de fondo**: Azul → Cian → Teal
- **Iconos**: FontAwesome para todos los elementos

---

## 📱 RESPONSIVE

Todo el sistema es responsive y se adapta a:
- Desktop (1920px+)
- Laptop (1366px - 1920px)
- Tablet (768px - 1366px)
- Mobile (< 768px)

---

## ⚙️ TECNOLOGÍAS UTILIZADAS

- **Backend:** Laravel 12.35.0
- **Frontend:** Blade Templates, HTML5, CSS3, JavaScript
- **Database:** MariaDB 10.4.32
- **Estilos:** Tailwind CSS + Custom CSS
- **Iconos:** FontAwesome
- **JavaScript:** jQuery
- **Componentes:** ModernComponents.js (custom)
- **Exportación:** DomPDF, Maatwebsite Excel

---

## 📞 SOPORTE

Para cualquier duda o problema con el sistema, consultar esta documentación completa.

**Última actualización:** 14 de Noviembre de 2025
**Versión del Sistema:** 1.0.0

---

## 🔄 ACTUALIZACIONES RECIENTES

### Noviembre 2025
- ✅ Módulo de Insumos completado
  - Eliminado campo "nombre_insumo"
  - Solo 4 unidades de medida: kg, g, L, ml
  - Responsable fijo: Lucia Cruz Farfan
  - Vista con tarjetas y alertas de vencimiento

- ✅ Módulo de Fosa Séptica completado
  - Campos autollenados (fechas, responsable, empresa)
  - Cálculo automático de próxima limpieza (+5 meses)
  - Vista con diseño de tarjetas modernas

- ✅ Módulo de Mantenimiento actualizado
  - Selección múltiple de equipos
  - Lista predefinida de equipos
  - Supervisión fija por Lucia Cruz Farfan

- ✅ Paleta de colores unificada
  - Solo azul/cian/teal
  - Eliminados colores naranjas y verdes
  - Gradientes consistentes en todo el sistema

---

**FIN DE LA DOCUMENTACIÓN**

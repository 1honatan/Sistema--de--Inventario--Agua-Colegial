# SISTEMA DE INVENTARIO AGUA COLEGIAL
## Manual de Usuario - Guía Completa del Sistema

---

## TABLA DE CONTENIDOS

1. [Información General del Sistema](#información-general-del-sistema)
2. [Credenciales de Acceso](#credenciales-de-acceso)
3. [Panel de Administrador](#panel-de-administrador)
4. [Panel de Personal (Rol Producción)](#panel-de-personal-rol-producción)
5. [Flujos de Trabajo Detallados](#flujos-de-trabajo-detallados)
6. [Características del Sistema](#características-del-sistema)

---

## INFORMACIÓN GENERAL DEL SISTEMA

### Descripción
Sistema de gestión integral para Agua Colegial que controla:
- Inventario de productos
- Producción diaria
- Salidas de productos
- Control de insumos
- Mantenimiento de equipos
- Limpieza de tanques y fosa séptica
- Control de fumigación
- Asistencia del personal
- Reportes y estadísticas

### URL de Acceso
```
http://127.0.0.1:8000
```

---

## CREDENCIALES DE ACCESO

### Administrador
- **Email:** admin@colegial.com
- **Contraseña:** admin123
- **Rol:** admin
- **Acceso:** Total al sistema

### Personal de Producción
- **Email:** anderson.aguilar@aguacolegial.com
- **Contraseña:** anderson123
- **Rol:** produccion
- **Nombre:** Anderson Aguilar
- **Cargo:** Encargado de Producción
- **Acceso:** Limitado a módulos específicos

---

## PANEL DE ADMINISTRADOR

### 1. INICIO DE SESIÓN

**Paso 1:** Abrir navegador y acceder a `http://127.0.0.1:8000`

**Paso 2:** Ingresar credenciales de administrador
- Email: admin@colegial.com
- Contraseña: admin123

**Paso 3:** Click en botón "Iniciar Sesión"

**Paso 4:** El sistema redirige automáticamente al Dashboard del Administrador

---

### 2. DASHBOARD PRINCIPAL (INICIO)

**Ubicación:** `http://127.0.0.1:8000/admin/dashboard`

**Elementos visibles:**
- **Tarjetas de estadísticas:**
  - Total de Personal
  - Productos en Inventario
  - Productos Bajos en Stock
  - Alertas Activas

- **Gráficos:**
  - Producción mensual
  - Ventas por producto
  - Estado del inventario

- **Alertas:**
  - Productos con stock bajo
  - Mantenimientos pendientes
  - Notificaciones importantes

**Acciones disponibles:**
- Ver estadísticas en tiempo real
- Acceder a módulos rápidamente desde tarjetas
- Revisar alertas del sistema

---

### 3. MENÚ LATERAL DEL ADMINISTRADOR

El sidebar muestra las siguientes opciones:

#### SECCIÓN: PRINCIPAL

**1. Inicio**
- Dashboard principal
- Estadísticas generales

**2. Registro del Personal**
- Ruta: `/control/asistencia-semanal/registro-rapido`
- Registrar asistencia diaria del personal
- Marcar entradas y salidas
- Registrar ausencias, permisos, tardanzas

**3. Inventario General**
- Ruta: `/inventario`
- Ver todos los productos
- Stock disponible
- Historial de movimientos

#### SECCIÓN: CONTROLES

**4. Productos Producidos Diarios**
- Ruta: `/control/produccion`
- Registrar producción diaria
- Tipos: Botellones, Bolsas
- Asignar responsable
- Actualiza inventario automáticamente

**5. Salidas Productos Diarios**
- Ruta: `/control/salidas`
- Registrar ventas/salidas
- Tipos: Venta, Donación, Muestra, Devolución
- Asignar vehículo y responsable
- Descuenta inventario automáticamente

**6. Control de Insumos**
- Ruta: `/control/insumos`
- Gestionar insumos de producción
- Control de stock de insumos

**7. Mantenimiento de Equipo**
- Ruta: `/control/mantenimiento`
- Programar mantenimientos
- Registrar reparaciones

**8. Limpieza Tanques de Agua**
- Ruta: `/control/tanques`
- Programar limpiezas
- Historial de mantenimiento

**9. Limpieza Fosa Séptica**
- Ruta: `/control/fosa-septica`
- Control de limpieza
- Registros sanitarios

**10. Control de Fumigación**
- Ruta: `/control/fumigacion`
- Programar fumigaciones
- Control de plagas

#### SECCIÓN: GESTIÓN

**11. Gestión de Vehículos**
- Ruta: `/admin/vehiculos`
- Registrar vehículos
- Asignar a conductores
- Control de mantenimiento

**12. Reportes**
- Ruta: `/admin/reportes`
- Generar reportes PDF
- Reportes de producción
- Reportes de ventas
- Reportes de inventario

#### SECCIÓN: ADMINISTRACIÓN

**13. Configuración del Sistema**
- Ruta: `/admin/configuracion`
- Configurar parámetros
- Gestionar usuarios
- Gestionar roles

---

### 4. REGISTRO DE ASISTENCIA DEL PERSONAL (ADMINISTRADOR)

**Ruta:** `/control/asistencia-semanal/registro-rapido`

**Paso a paso:**

**Paso 1:** Click en "Registro del Personal" en el menú lateral

**Paso 2:** Se muestra la vista con tarjetas de todo el personal:
- Nombre completo
- Cargo
- Campos de hora entrada (06:00 predefinido)
- Campos de hora salida (18:00 predefinido)
- Selector de estado (Presente, Ausente, Permiso, Tardanza)

**Paso 3:** Para cada empleado, el administrador puede:

**Opción A - Registrar Presente:**
- Ajustar hora de entrada (ej: 06:00)
- Ajustar hora de salida (ej: 18:00)
- Seleccionar "Presente" en el selector
- Click en botón "Agregar"

**Opción B - Registrar Ausente:**
- Seleccionar "Ausente"
- Agregar observaciones (motivo)
- Click en botón "Agregar"

**Opción C - Registrar Permiso:**
- Seleccionar "Permiso"
- Agregar observaciones
- Click en botón "Agregar"

**Opción D - Registrar Tardanza:**
- Ajustar hora real de entrada
- Seleccionar "Tardanza"
- Agregar observaciones
- Click en botón "Agregar"

**Paso 4:** El sistema guarda automáticamente:
- Fecha del día
- Día de la semana
- Horas trabajadas (calculadas automáticamente)
- Quién registró (ID del administrador)

**Paso 5:** Confirmación visual:
- Mensaje de éxito
- Actualización de la vista

---

### 5. GESTIÓN DE INVENTARIO (ADMINISTRADOR)

**Ruta:** `/inventario`

**Paso a paso:**

**Paso 1:** Click en "Inventario General" en el menú

**Paso 2:** Vista de inventario muestra:
- **Tabla con:**
  - Código del producto
  - Nombre del producto
  - Categoría
  - Stock disponible
  - Stock mínimo
  - Alertas de stock bajo

**Paso 3:** Ver detalles de un producto:
- Click en nombre del producto
- Muestra historial completo de movimientos
- Gráficos de tendencias

**Paso 4:** Historial de movimientos muestra:
- Fecha y hora
- Tipo de movimiento (Entrada/Salida)
- Cantidad
- Stock resultante
- Usuario responsable
- Observaciones

---

### 6. REGISTRO DE PRODUCCIÓN DIARIA (ADMINISTRADOR)

**Ruta:** `/control/produccion`

**Paso a paso:**

**Paso 1:** Click en "Productos Producidos Diarios" en menú

**Paso 2:** Click en botón "Nuevo Registro de Producción Diaria"

**Paso 3:** Formulario de registro:

**Campo 1 - Fecha:**
- Auto-completado con fecha actual
- Campo de solo lectura (no editable)
- Formato: YYYY-MM-DD

**Campo 2 - Tipo de Producto:**
- Selector desplegable
- Opciones: "Botellones" o "Bolsas"
- Campo obligatorio

**Campo 3 - Cantidad Producida:**
- Campo numérico
- Mínimo: 1
- Campo obligatorio
- Ejemplo: 150

**Campo 4 - Responsable:**
- Selector desplegable con todo el personal
- Muestra: Nombre (Cargo)
- **IMPORTANTE:** Si el usuario tiene rol "produccion", su nombre se auto-selecciona
- Campo obligatorio

**Campo 5 - Observaciones:**
- Campo de texto largo (opcional)
- Para notas adicionales
- Ejemplo: "Producción de lote #123"

**Paso 4:** Click en botón "Registrar Producción"

**Paso 5:** El sistema automáticamente:
- Guarda el registro de producción
- **ACTUALIZA EL INVENTARIO:** Suma la cantidad al stock del producto
- Registra quién hizo el registro
- Guarda fecha y hora del registro

**Paso 6:** Redirección a lista de producción con mensaje de éxito

**Resultado en inventario:**
- Si había 100 botellones y se producen 50
- Nuevo stock: 150 botellones
- Se crea movimiento de entrada en historial

---

### 7. REGISTRO DE SALIDAS DE PRODUCTOS (ADMINISTRADOR)

**Ruta:** `/control/salidas`

**Paso a paso:**

**Paso 1:** Click en "Salidas Productos Diarios" en menú

**Paso 2:** Click en botón "Nuevo Registro de Salida"

**Paso 3:** Formulario de registro:

**Campo 1 - Fecha:**
- Auto-completado con fecha actual
- Campo de solo lectura
- Formato: YYYY-MM-DD

**Campo 2 - Tipo de Salida:**
- Selector desplegable
- Opciones:
  - Venta
  - Donación
  - Muestra
  - Devolución
- Campo obligatorio

**Campo 3 - Producto:**
- Selector desplegable
- Muestra todos los productos del inventario
- Muestra stock disponible
- Campo obligatorio

**Campo 4 - Cantidad:**
- Campo numérico
- Mínimo: 1
- Máximo: Stock disponible
- **VALIDACIÓN:** No puede exceder el stock
- Campo obligatorio

**Campo 5 - Cliente/Destino:**
- Campo de texto
- Nombre del cliente o destino
- Campo obligatorio
- Ejemplo: "Tienda El Centro"

**Campo 6 - Vehículo:**
- Selector desplegable
- Muestra vehículos registrados
- Formato: Placa - Marca Modelo
- Campo obligatorio

**Campo 7 - Responsable de Venta:**
- Selector desplegable con personal
- **IMPORTANTE:** Si el usuario tiene rol "produccion", su nombre se auto-selecciona
- Campo obligatorio

**Campo 8 - Observaciones:**
- Campo de texto largo (opcional)
- Para notas adicionales

**Paso 4:** Click en botón "Registrar Salida"

**Paso 5:** El sistema automáticamente:
- Guarda el registro de salida
- **ACTUALIZA EL INVENTARIO:** Resta la cantidad del stock del producto
- Registra el movimiento en historial
- Asocia el vehículo utilizado
- Guarda quién hizo la venta

**Paso 6:** Redirección con mensaje de éxito

**Resultado en inventario:**
- Si había 150 botellones y se venden 30
- Nuevo stock: 120 botellones
- Se crea movimiento de salida en historial

---

### 8. REPORTES (ADMINISTRADOR)

**Ruta:** `/admin/reportes`

**Paso a paso:**

**Paso 1:** Click en "Reportes" en el menú

**Paso 2:** Seleccionar tipo de reporte:

**Opción A - Reporte de Producción:**
- Seleccionar rango de fechas
- Filtrar por producto (opcional)
- Filtrar por responsable (opcional)
- Click en "Generar PDF"
- Descarga PDF con:
  - Total producido por producto
  - Producción diaria
  - Gráficos de tendencias
  - Responsables de producción

**Opción B - Reporte de Ventas:**
- Seleccionar rango de fechas
- Filtrar por tipo de salida
- Filtrar por producto
- Click en "Generar PDF"
- Descarga PDF con:
  - Total de salidas
  - Ventas por producto
  - Clientes principales
  - Vehículos utilizados

**Opción C - Reporte de Inventario:**
- Click en "Generar PDF"
- Descarga PDF con:
  - Stock actual de todos los productos
  - Productos con stock bajo
  - Valor total del inventario
  - Movimientos recientes

**Opción D - Reporte de Asistencias:**
- Seleccionar rango de fechas
- Filtrar por personal
- Click en "Generar PDF"
- Descarga PDF con:
  - Asistencias del período
  - Total de horas trabajadas
  - Ausencias y permisos
  - Estadísticas por empleado

---

### 9. GESTIÓN DE VEHÍCULOS (ADMINISTRADOR)

**Ruta:** `/admin/vehiculos`

**Paso a paso:**

**Paso 1:** Click en "Gestión de Vehículos" en menú

**Paso 2:** Ver lista de vehículos registrados

**Paso 3:** Para agregar nuevo vehículo:
- Click en "Nuevo Vehículo"
- Llenar formulario:
  - Placa
  - Marca
  - Modelo
  - Año
  - Conductor asignado
  - Estado (Activo/Mantenimiento/Inactivo)
- Click en "Guardar"

**Paso 4:** Los vehículos registrados estarán disponibles en:
- Registro de salidas de productos
- Asignación de rutas
- Control de mantenimiento

---

## PANEL DE PERSONAL (ROL PRODUCCIÓN)

### 1. INICIO DE SESIÓN

**Paso 1:** Abrir navegador y acceder a `http://127.0.0.1:8000`

**Paso 2:** Ingresar credenciales de personal
- Email: anderson.aguilar@aguacolegial.com
- Contraseña: anderson123

**Paso 3:** Click en botón "Iniciar Sesión"

**Paso 4:** El sistema redirige automáticamente a `/control/produccion` (Productos Producidos Diarios)

---

### 2. MENÚ LATERAL DEL PERSONAL

El personal con rol "produccion" ve un menú simplificado:

**1. Inventario**
- Ruta: `/inventario`
- **ACCESO:** Solo lectura
- Ver stock de productos
- Ver historial de movimientos
- **NO PUEDE:** Editar o crear productos

**SECCIÓN: CONTROLES**

**2. Productos Producidos Diarios**
- Ruta: `/control/produccion`
- **ACCESO:** Completo
- Ver lista de producciones
- Crear nuevo registro
- **AUTO-COMPLETA:** Su nombre como responsable
- Editar registros
- Eliminar registros

**3. Salidas Productos Diarios**
- Ruta: `/control/salidas`
- **ACCESO:** Completo
- Ver lista de salidas
- Crear nuevo registro
- **AUTO-COMPLETA:** Su nombre como responsable de venta
- Editar registros
- Eliminar registros

**4. Control de Insumos**
- Ruta: `/control/insumos`
- **ACCESO:** Completo
- Gestionar insumos

**5. Mantenimiento de Equipo**
- Ruta: `/control/mantenimiento`
- **ACCESO:** Completo
- Registrar mantenimientos

**6. Limpieza Tanques de Agua**
- Ruta: `/control/tanques`
- **ACCESO:** Completo
- Registrar limpiezas

**7. Limpieza Fosa Séptica**
- Ruta: `/control/fosa-septica`
- **ACCESO:** Completo
- Registrar limpiezas

**8. Control de Fumigación**
- Ruta: `/control/fumigacion`
- **ACCESO:** Completo
- Registrar fumigaciones

**9. Reportes**
- Ruta: `/admin/reportes`
- **ACCESO:** Solo lectura
- Ver y generar reportes
- **NO PUEDE:** Modificar configuraciones

**SECCIÓN: MI ASISTENCIA**

**10. Mi Historial**
- Ruta: `/personal/asistencia/historial`
- **ACCESO:** Solo lectura
- Ver su propio historial de asistencias
- **NO PUEDE:** Registrar su propia asistencia
- Ver estadísticas del mes:
  - Total de días trabajados
  - Horas trabajadas
  - Ausencias
  - Permisos

---

### 3. FLUJO DE TRABAJO DEL PERSONAL - REGISTRO DE PRODUCCIÓN

**Escenario típico del día:**

**Paso 1:** Anderson inicia sesión a las 6:00 AM

**Paso 2:** El sistema lo lleva automáticamente a `/control/produccion`

**Paso 3:** Ve la lista de producciones del día actual (si hay)

**Paso 4:** Click en "Nuevo Registro de Producción Diaria"

**Paso 5:** Formulario con datos pre-llenados:
- **Fecha:** 2025-11-27 (auto, bloqueado)
- **Tipo de Producto:** [Selecciona "Botellones"]
- **Cantidad Producida:** [Ingresa "150"]
- **Responsable:** "Anderson Aguilar (Encargado de Producción)" [AUTO-SELECCIONADO]
- **Observaciones:** [Opcional, ej: "Lote matutino"]

**Paso 6:** Click en "Registrar Producción"

**Paso 7:** Sistema confirma:
- "Producción registrada exitosamente"
- Inventario actualizado: +150 botellones
- Registro visible en la lista

**Paso 8:** Durante el día, puede registrar más lotes:
- 10:00 AM - 100 bolsas
- 2:00 PM - 80 botellones
- Cada registro actualiza el inventario automáticamente

---

### 4. FLUJO DE TRABAJO DEL PERSONAL - REGISTRO DE SALIDAS

**Escenario típico:**

**Paso 1:** Cliente llega a comprar productos

**Paso 2:** Anderson va a "Salidas Productos Diarios" en el menú

**Paso 3:** Click en "Nuevo Registro de Salida"

**Paso 4:** Llena el formulario:
- **Fecha:** 2025-11-27 (auto, bloqueado)
- **Tipo de Salida:** [Selecciona "Venta"]
- **Producto:** [Selecciona "Botellones 20 litros"]
- **Cantidad:** [Ingresa "50"]
- **Cliente/Destino:** [Ingresa "Tienda Don Pedro"]
- **Vehículo:** [Selecciona "ABC-123 - Toyota Hilux"]
- **Responsable de Venta:** "Anderson Aguilar (Encargado de Producción)" [AUTO-SELECCIONADO]
- **Observaciones:** [Opcional]

**Paso 5:** Click en "Registrar Salida"

**Paso 6:** Sistema confirma:
- "Salida registrada exitosamente"
- Inventario actualizado: -50 botellones
- Si había 200, ahora quedan 150

**Paso 7:** Puede ver el registro en la lista de salidas

---

### 5. CONSULTA DE INVENTARIO (PERSONAL)

**Paso 1:** Click en "Inventario" en el menú lateral

**Paso 2:** Ver tabla con todos los productos:
- Código
- Nombre
- Stock disponible
- Stock mínimo
- Alertas de stock bajo

**Paso 3:** **NO PUEDE:**
- Crear productos nuevos
- Editar productos existentes
- Eliminar productos
- Ajustar stock manualmente

**Paso 4:** **SÍ PUEDE:**
- Ver historial de movimientos
- Ver quién hizo cada movimiento
- Consultar stock en tiempo real
- Ver alertas de productos bajos

---

### 6. VER MI HISTORIAL DE ASISTENCIA (PERSONAL)

**Ruta:** `/personal/asistencia/historial`

**Paso 1:** Click en "Mi Historial" en la sección "Mi Asistencia"

**Paso 2:** Vista muestra:

**Mensaje informativo:**
```
Su asistencia es registrada únicamente por el administrador del sistema.
Aquí puede consultar su historial de asistencias, entradas, salidas y ausencias.
Para cualquier corrección o consulta sobre su asistencia, por favor contacte al administrador.
```

**Estadísticas del mes actual:**
- Total de días trabajados
- Días de entrada registrados
- Días de salida registrados
- Total de ausencias
- Total de horas trabajadas

**Paso 3:** Tabla de historial (últimos 30 días):
- Fecha
- Estado (Presente/Ausente/Permiso/Tardanza)
- Hora de entrada
- Hora de salida
- Horas trabajadas
- Observaciones

**Ejemplo de registro:**
```
Fecha: 25/11/2025
Estado: Presente ✓ (verde)
Hora Entrada: 06:00
Hora Salida: 18:00
Horas Trabajadas: 12.00 hrs
Observaciones: -
```

**Paso 4:** Filtrar por rango de fechas:
- Seleccionar fecha inicio
- Seleccionar fecha fin
- Click en "Buscar"

**Paso 5:** **NO PUEDE:**
- Registrar su propia asistencia
- Modificar registros existentes
- Eliminar registros

---

## FLUJOS DE TRABAJO DETALLADOS

### FLUJO 1: CICLO COMPLETO DE PRODUCCIÓN Y VENTA

**Día típico en Agua Colegial:**

**6:00 AM - Inicio del día**

**1. Administrador registra asistencia:**
- Login como admin
- Va a "Registro del Personal"
- Marca a Anderson Aguilar como "Presente" (06:00 - 18:00)
- Marca a otros empleados según asistencia

**6:30 AM - Anderson inicia trabajo:**

**2. Anderson registra producción matutina:**
- Login como anderson.aguilar@aguacolegial.com
- Sistema lo lleva a "Productos Producidos Diarios"
- Click "Nuevo Registro"
- Tipo: Botellones
- Cantidad: 200
- Su nombre auto-seleccionado
- Click "Registrar"
- **RESULTADO:** Inventario +200 botellones

**10:00 AM - Primera venta del día:**

**3. Anderson registra venta:**
- Va a "Salidas Productos Diarios"
- Click "Nuevo Registro"
- Tipo: Venta
- Producto: Botellones 20 litros
- Cantidad: 50
- Cliente: Tienda Los Pinos
- Vehículo: ABC-123
- Su nombre auto-seleccionado
- Click "Registrar"
- **RESULTADO:** Inventario -50 botellones (quedan 150)

**12:00 PM - Segunda producción:**

**4. Anderson registra producción de bolsas:**
- "Nuevo Registro de Producción"
- Tipo: Bolsas
- Cantidad: 300
- Click "Registrar"
- **RESULTADO:** Inventario +300 bolsas

**2:00 PM - Donación:**

**5. Anderson registra donación:**
- "Nuevo Registro de Salida"
- Tipo: Donación
- Producto: Bolsas 500ml
- Cantidad: 50
- Cliente: Escuela Municipal
- Vehículo: XYZ-789
- Click "Registrar"
- **RESULTADO:** Inventario -50 bolsas (quedan 250)

**5:00 PM - Fin del día:**

**6. Administrador genera reporte:**
- Login como admin
- Va a "Reportes"
- Selecciona "Reporte de Producción"
- Rango: Hoy
- Click "Generar PDF"
- **RESULTADO:** PDF con:
  - 200 botellones producidos
  - 300 bolsas producidas
  - 50 botellones vendidos
  - 50 bolsas donadas
  - Responsable: Anderson Aguilar

---

### FLUJO 2: CONTROL DE INVENTARIO Y ALERTAS

**Escenario: Stock bajo**

**Paso 1:** Producción normal consume stock

**Paso 2:** Stock de botellones llega a nivel mínimo:
- Stock actual: 15 botellones
- Stock mínimo: 20 botellones

**Paso 3:** Sistema genera alerta automática:
- Notificación en dashboard
- Tarjeta "Productos Bajos en Stock"
- Icono rojo en inventario

**Paso 4:** Administrador ve alerta:
- Dashboard muestra: "1 producto con stock bajo"
- Click en alerta

**Paso 5:** Administrador toma acción:
- Ordena nueva producción
- Asigna a personal de producción

**Paso 6:** Personal registra nueva producción:
- Produce 500 botellones
- Inventario se actualiza
- Alerta desaparece automáticamente

---

### FLUJO 3: ASISTENCIA DEL PERSONAL

**Día completo de asistencia:**

**Mañana (6:00 AM):**

**1. Administrador registra entrada:**
- Login como admin
- Va a "Registro del Personal"
- Encuentra tarjeta de Anderson Aguilar
- Hora entrada: 06:00 (predefinida)
- Hora salida: 18:00 (predefinida)
- Estado: Presente
- Click "Agregar"

**2. Sistema guarda:**
- Fecha: 2025-11-27
- Día: Miércoles
- Entrada: 06:00
- Salida: 18:00
- Estado: Presente
- Registrado por: Admin

**Durante el día:**

**3. Anderson consulta su historial:**
- Login como anderson.aguilar@aguacolegial.com
- Click en "Mi Historial" (sidebar)
- Ve su asistencia del día:
  - Fecha: 27/11/2025
  - Estado: Presente ✓
  - Entrada: 06:00
  - Salida: 18:00
  - Horas: 12.00 hrs

**Fin de mes:**

**4. Administrador genera reporte de asistencias:**
- Va a "Reportes"
- Selecciona "Reporte de Asistencias"
- Rango: Todo noviembre 2025
- Personal: Anderson Aguilar
- Click "Generar PDF"

**5. PDF muestra:**
- Total días trabajados: 22
- Total horas: 264.00 hrs
- Ausencias: 1
- Permisos: 0
- Tardanzas: 2

---

## CARACTERÍSTICAS DEL SISTEMA

### AUTO-COMPLETADO PARA ROL PRODUCCIÓN

**¿Qué se auto-completa?**

**1. Fecha en formularios:**
- Siempre la fecha actual
- Campo bloqueado (no editable)
- Formato: YYYY-MM-DD

**2. Responsable en Producción:**
- Si usuario tiene rol "produccion"
- Su nombre se selecciona automáticamente
- Puede cambiarse si es necesario
- Evita errores de selección

**3. Responsable de Venta en Salidas:**
- Si usuario tiene rol "produccion"
- Su nombre se selecciona automáticamente
- Facilita el registro rápido

---

### ACTUALIZACIÓN AUTOMÁTICA DE INVENTARIO

**Sistema de doble entrada:**

**Entrada (Producción):**
```
Registro de Producción:
- Cantidad: +150 botellones
- Acción automática: inventory.stock += 150
- Genera movimiento tipo "Entrada"
- Fecha y hora del registro
- Usuario responsable
```

**Salida (Ventas/Donaciones):**
```
Registro de Salida:
- Cantidad: -50 botellones
- Validación: stock >= 50
- Acción automática: inventory.stock -= 50
- Genera movimiento tipo "Salida"
- Fecha y hora del registro
- Usuario responsable
```

**Historial de movimientos:**
```
Cada movimiento registra:
- Tipo: Entrada/Salida
- Cantidad: +/- número
- Stock anterior
- Stock resultante
- Fecha y hora exacta
- Usuario que hizo el movimiento
- Observaciones
```

---

### VALIDACIONES DEL SISTEMA

**1. Validación de stock en salidas:**
```
if (cantidad_solicitada > stock_disponible) {
    ERROR: "No hay suficiente stock. Disponible: X unidades"
}
```

**2. Validación de cantidades:**
```
- Cantidad mínima: 1
- Cantidad máxima: 999999
- Solo números enteros positivos
```

**3. Validación de fechas:**
```
- No permite fechas futuras en la mayoría de registros
- Fecha debe ser válida
- Formato correcto
```

**4. Validación de roles:**
```
if (usuario.rol !== 'admin' && acceso.requiere_admin) {
    ERROR 403: "Acceso denegado"
}
```

---

### SISTEMA DE PERMISOS POR ROL

**ROL: admin**
```
PUEDE:
✓ Ver todo
✓ Crear todo
✓ Editar todo
✓ Eliminar todo
✓ Gestionar usuarios
✓ Gestionar configuración
✓ Registrar asistencia de otros
✓ Ver todos los reportes
✓ Modificar inventario manualmente
```

**ROL: produccion (Personal)**
```
PUEDE:
✓ Ver inventario (solo lectura)
✓ Registrar producción (su nombre auto-completa)
✓ Registrar salidas (su nombre auto-completa)
✓ Gestionar todos los controles
✓ Ver reportes (solo lectura)
✓ Ver su propio historial de asistencia

NO PUEDE:
✗ Registrar su propia asistencia
✗ Ver asistencia de otros
✗ Modificar configuración del sistema
✗ Gestionar usuarios
✗ Editar productos en inventario
✗ Eliminar su propio historial
```

---

### CÁLCULOS AUTOMÁTICOS

**1. Horas trabajadas:**
```
entrada = 06:00
salida = 18:00
horas_trabajadas = salida - entrada = 12.00 hrs

Si solo hay entrada:
horas_trabajadas = 0 (pendiente de salida)

Si es ausente/permiso:
horas_trabajadas = 0
```

**2. Stock después de movimiento:**
```
Stock anterior: 100 botellones
Producción: +150 botellones
Stock nuevo = 100 + 150 = 250 botellones

Stock anterior: 250 botellones
Venta: -50 botellones
Stock nuevo = 250 - 50 = 200 botellones
```

**3. Estadísticas mensuales:**
```
Total días = count(asistencias_del_mes)
Total horas = sum(horas_trabajadas)
Ausencias = count(estado === 'ausente')
Presentes = count(estado === 'presente')
```

---

### INTERFAZ DE USUARIO

**Diseño responsivo:**
- Desktop: Sidebar expandido (220px)
- Mobile: Sidebar colapsable
- Tablet: Sidebar adaptativo

**Tamaño de fuente:**
- 80% del tamaño base
- Optimizado para más información en pantalla

**Colores de estados:**
- Verde: Presente, Éxito, Activo
- Rojo: Ausente, Error, Stock bajo
- Amarillo: Permiso, Advertencia
- Naranja: Tardanza, Alerta media
- Azul: Información, Enlaces

**Iconos Font Awesome:**
- Cada módulo tiene icono identificativo
- Estados con íconos visuales
- Botones con íconos descriptivos

---

## RESUMEN DE RUTAS PRINCIPALES

### Administrador
```
Login: http://127.0.0.1:8000/login
Dashboard: http://127.0.0.1:8000/admin/dashboard
Registro Personal: http://127.0.0.1:8000/control/asistencia-semanal/registro-rapido
Inventario: http://127.0.0.1:8000/inventario
Producción: http://127.0.0.1:8000/control/produccion
Salidas: http://127.0.0.1:8000/control/salidas
Reportes: http://127.0.0.1:8000/admin/reportes
Vehículos: http://127.0.0.1:8000/admin/vehiculos
Configuración: http://127.0.0.1:8000/admin/configuracion
```

### Personal (Producción)
```
Login: http://127.0.0.1:8000/login
Inicio (Producción): http://127.0.0.1:8000/control/produccion
Inventario: http://127.0.0.1:8000/inventario (solo lectura)
Salidas: http://127.0.0.1:8000/control/salidas
Mi Historial: http://127.0.0.1:8000/personal/asistencia/historial
Reportes: http://127.0.0.1:8000/admin/reportes (solo lectura)
```

---

## SOPORTE Y MANTENIMIENTO

### Limpiar caché del sistema:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Comandos útiles:
```bash
# Iniciar servidor
php artisan serve --host=127.0.0.1 --port=8000

# Migrar base de datos
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Ver rutas
php artisan route:list
```

---

**Fecha de creación:** 27 de Noviembre de 2025
**Sistema:** Agua Colegial - Sistema de Inventario y Control
**Versión:** 1.0
**Desarrollado para:** Agua Colegial

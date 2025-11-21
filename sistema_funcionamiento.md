# Manual de Funcionamiento del Sistema - Agua Colegial

## Descripción General
Sistema de Gestión Integral para la empresa Agua Colegial, diseñado para controlar la producción, inventario, personal, vehículos y todos los procesos operativos de la planta.

---

## 1. ACCESO AL SISTEMA

### 1.1 Inicio de Sesión
1. Abrir el navegador web
2. Ingresar a la URL del sistema: `http://localhost/agua_colegial/public`
3. Se muestra la pantalla de **Login**
4. Ingresar credenciales:
   - **Email**: Correo electrónico registrado
   - **Contraseña**: Contraseña asignada
5. Hacer clic en el botón **"Iniciar Sesión"**
6. El sistema valida las credenciales
7. Si son correctas, redirige al **Dashboard Principal**
8. Si son incorrectas, muestra mensaje de error

---

## 2. DASHBOARD PRINCIPAL (Inicio)

### 2.1 Vista General
Al ingresar al sistema se muestra:
- **Tarjetas de estadísticas**:
  - Total Producción del día
  - Stock en Inventario
  - Total Salidas
  - Personal Activo
- **Gráficos de producción**: Visualización de producción por período
- **Últimas Salidas**: Lista de las salidas más recientes
- **Productos con Stock Bajo**: Alertas de productos que necesitan reposición

### 2.2 Navegación
- Menú lateral izquierdo con todas las opciones del sistema
- Barra superior con nombre del usuario y opción de cerrar sesión

---

## 3. REGISTRO DEL PERSONAL

### 3.1 Acceder al Módulo
1. En el menú lateral, hacer clic en **"Registro del Personal"**
2. Se muestra la lista de todo el personal registrado

### 3.2 Registrar Nuevo Personal
1. Hacer clic en el botón **"Nuevo Personal"**
2. Completar el formulario:
   - **Nombre Completo**: Nombre y apellidos del empleado
   - **Cédula/DNI**: Documento de identidad
   - **Cargo**: Puesto que ocupa (Operador, Distribuidor, Administrativo, etc.)
   - **Teléfono**: Número de contacto
   - **Email**: Correo electrónico
   - **Fecha de Ingreso**: Fecha en que comenzó a trabajar
   - **Estado**: Activo/Inactivo
3. Hacer clic en **"Guardar"**
4. El sistema muestra mensaje de confirmación
5. El empleado aparece en la lista

### 3.3 Editar Personal
1. En la lista, ubicar el empleado a modificar
2. Hacer clic en el botón **"Editar"** (icono de lápiz)
3. Modificar los campos necesarios
4. Hacer clic en **"Actualizar"**

### 3.4 Eliminar Personal
1. Hacer clic en el botón **"Eliminar"** (icono de papelera)
2. Confirmar la eliminación en el diálogo
3. El registro se elimina del sistema

---

## 4. INVENTARIO GENERAL

### 4.1 Acceder al Módulo
1. En el menú lateral, hacer clic en **"Inventario General"**
2. Se muestra la lista de todos los productos con su stock actual

### 4.2 Vista del Inventario
- **Producto**: Nombre del producto
- **Tipo**: Categoría del producto
- **Stock Actual**: Cantidad disponible
- **Unidad de Medida**: Unidades, litros, kg, etc.
- **Estado**: Indicador visual (Normal, Stock Bajo, Sin Stock)

### 4.3 Registrar Movimiento de Inventario
1. Hacer clic en **"Nuevo Movimiento"**
2. Completar el formulario:
   - **Producto**: Seleccionar de la lista
   - **Tipo de Movimiento**: Entrada o Salida
   - **Cantidad**: Número de unidades
   - **Origen/Destino**: De dónde viene o a dónde va
   - **Referencia**: Número de documento o referencia
   - **Fecha**: Fecha del movimiento
   - **Observación**: Notas adicionales
3. Hacer clic en **"Guardar"**
4. El stock se actualiza automáticamente

### 4.4 Ver Historial de Movimientos
1. Hacer clic en el producto deseado
2. Se muestra el historial completo de entradas y salidas

---

## 5. CONTROLES

### 5.1 Productos Producidos Diarios

#### Acceder
1. Menú lateral → Controles → **"Productos Producidos Diarios"**

#### Registrar Producción
1. Hacer clic en **"Nueva Producción"**
2. Completar el formulario:
   - **Fecha**: Fecha de producción
   - **Turno**: Mañana/Tarde/Noche (si aplica)
   - **Productos**: Para cada producto producido:
     - Seleccionar el producto
     - Ingresar cantidad producida
   - **Materiales Utilizados**: Insumos consumidos
   - **Observaciones**: Notas del proceso
3. Hacer clic en **"Guardar"**
4. El inventario se actualiza automáticamente (entrada de productos)

#### Ver Producciones
- Lista todas las producciones registradas
- Filtrar por fecha
- Ver detalle de cada producción

---

### 5.2 Salidas Productos Diarios

#### Acceder
1. Menú lateral → Controles → **"Salidas Productos Diarios"**

#### Registrar Nueva Salida
1. Hacer clic en **"Nueva Salida"**
2. Completar el formulario:
   - **Responsable/Distribuidor**: Seleccionar de la lista de personal
   - **Vehículo**: Seleccionar placa del vehículo (se autocompleta según responsable)
   - **Fecha**: Fecha de la salida
   - **Hora de Llegada**: Hora estimada de retorno

3. **Detalle de Productos** (cantidades a entregar):
   - Botellones de 20 litros
   - Bolo Grande
   - Bolo Pequeño
   - Gelatina
   - Agua Saborizada
   - Agua de Limón
   - Agua Natural
   - Bolsa de Hielo
   - Dispenser

4. **Detalle de Retornos** (envases que regresan):
   - Ingresar cantidad de cada producto retornado
   - Chorreados (envases dañados)

5. **Observaciones**: Notas adicionales
6. Hacer clic en **"Guardar"**
7. El sistema:
   - Registra la salida
   - Descuenta del inventario (salidas)
   - Suma al inventario (retornos)
   - Muestra mensaje de confirmación

#### Ver Salidas Registradas
- Lista todas las salidas con:
  - Fecha
  - Distribuidor
  - Vehículo
  - Total botellones
  - Total retornos
- Opciones: Ver detalle, Editar, Eliminar, Generar PDF

---

### 5.3 Control de Insumos

#### Acceder
1. Menú lateral → Controles → **"Control de Insumos"**

#### Registrar Insumo
1. Hacer clic en **"Nuevo Insumo"**
2. Completar el formulario:
   - **Fecha**: Fecha de recepción
   - **Producto/Insumo**: Nombre del insumo
   - **Cantidad**: Cantidad recibida
   - **Unidad de Medida**: kg, litros, unidades, etc.
   - **Número de Lote**: Lote del proveedor
   - **Fecha de Vencimiento**: Caducidad del producto
   - **Proveedor**: Nombre del proveedor
   - **Responsable**: Quien recibe
   - **Observaciones**: Notas adicionales
3. Hacer clic en **"Guardar"**

#### Consultar Insumos
- Ver lista de todos los insumos
- Filtrar por fecha, proveedor, producto
- Controlar fechas de vencimiento

---

### 5.4 Mantenimiento de Equipo

#### Acceder
1. Menú lateral → Controles → **"Mantenimiento de Equipo"**

#### Registrar Mantenimiento
1. Hacer clic en **"Nuevo Mantenimiento"**
2. Completar el formulario:
   - **Fecha**: Fecha del mantenimiento
   - **Equipo(s)**: Seleccionar equipos (puede ser múltiple)
   - **Tipo de Mantenimiento**: Preventivo/Correctivo
   - **Descripción del Trabajo**: Detalle de lo realizado
   - **Realizado Por**: Técnico responsable
   - **Costo**: Monto del mantenimiento (opcional)
   - **Próxima Fecha**: Próximo mantenimiento programado
   - **Observaciones**: Notas adicionales
3. Hacer clic en **"Guardar"**

#### Seguimiento
- Ver historial de mantenimientos
- Alertas de mantenimientos próximos/vencidos
- Filtrar por equipo o fecha

---

### 5.5 Limpieza Tanques de Agua

#### Acceder
1. Menú lateral → Controles → **"Limpieza Tanques de Agua"**

#### Registrar Limpieza
1. Hacer clic en **"Nueva Limpieza"**
2. Completar el formulario:
   - **Fecha de Limpieza**: Fecha en que se realizó
   - **Nombre del Tanque**: Identificación del tanque
   - **Capacidad (Litros)**: Capacidad del tanque
   - **Procedimiento de Limpieza**: Descripción del proceso
   - **Productos de Desinfección**: Químicos utilizados
   - **Responsable**: Quien realizó la limpieza
   - **Supervisado Por**: Supervisor
   - **Próxima Limpieza**: Fecha programada
   - **Observaciones**: Notas adicionales
3. Hacer clic en **"Guardar"**

#### Consultar
- Ver historial de limpiezas por tanque
- Alertas de próximas limpiezas

---

### 5.6 Limpieza Fosa Séptica

#### Acceder
1. Menú lateral → Controles → **"Limpieza Fosa Séptica"**

#### Registrar Limpieza
1. Hacer clic en **"Nueva Limpieza"**
2. Completar el formulario:
   - **Fecha de Limpieza**: Fecha en que se realizó
   - **Tipo de Fosa**: Identificación de la fosa
   - **Detalle del Trabajo**: Descripción de lo realizado
   - **Responsable**: Quien coordinó
   - **Empresa Contratada**: Empresa que realizó el servicio
   - **Próxima Limpieza**: Fecha programada
   - **Observaciones**: Notas adicionales
3. Hacer clic en **"Guardar"**

---

### 5.7 Control de Fumigación

#### Acceder
1. Menú lateral → Controles → **"Control de Fumigación"**

#### Registrar Fumigación
1. Hacer clic en **"Nueva Fumigación"**
2. Completar el formulario:
   - **Fecha de Fumigación**: Fecha en que se realizó
   - **Área Fumigada**: Zona de la planta tratada
   - **Producto Utilizado**: Químico aplicado
   - **Cantidad de Producto**: Cantidad usada
   - **Responsable**: Quien coordinó
   - **Empresa Contratada**: Empresa fumigadora
   - **Próxima Fumigación**: Fecha programada
   - **Observaciones**: Notas adicionales
3. Hacer clic en **"Guardar"**

#### Seguimiento
- Ver historial de fumigaciones
- Controlar fechas de próximas fumigaciones
- Certificados de fumigación

---

## 6. GESTIÓN

### 6.1 Gestión de Vehículos

#### Acceder
1. Menú lateral → Gestión → **"Gestión de Vehículos"**

#### Registrar Vehículo
1. Hacer clic en **"Nuevo Vehículo"**
2. Completar el formulario:
   - **Placa**: Número de placa
   - **Marca**: Marca del vehículo
   - **Modelo**: Modelo
   - **Año**: Año de fabricación
   - **Color**: Color del vehículo
   - **Tipo**: Camión, camioneta, etc.
   - **Responsable**: Conductor asignado
   - **Estado**: Activo/Inactivo/En mantenimiento
3. Hacer clic en **"Guardar"**

#### Gestionar Vehículos
- Ver lista de todos los vehículos
- Editar información
- Cambiar estado
- Asignar/cambiar responsable

---

## 7. REPORTES

### 7.1 Acceder al Módulo
1. Menú lateral → **"Reportes"**
2. Se muestran todas las opciones de reportes disponibles

### 7.2 Tipos de Reportes Disponibles

#### Reportes de Control
- **Salidas de Productos**: Despachos realizados
- **Producción Diaria**: Productos fabricados
- **Mantenimiento**: Historial de mantenimientos
- **Fumigación**: Registro de fumigaciones
- **Fosa Séptica**: Limpiezas realizadas
- **Tanques de Agua**: Limpiezas de tanques
- **Insumos**: Control de insumos recibidos
- **Asistencia**: Control de personal

#### Reportes de Gestión
- **Inventario**: Stock actual y movimientos

### 7.3 Generar un Reporte

1. Seleccionar el tipo de reporte
2. Configurar filtros:
   - **Fecha Inicio**: Desde cuándo
   - **Fecha Fin**: Hasta cuándo
   - **Filtros adicionales** (según el reporte)
3. Hacer clic en **"Generar PDF"**
4. El sistema genera el documento PDF
5. El archivo se descarga automáticamente

### 7.4 Contenido de los Reportes
Cada reporte incluye:
- Encabezado con logo y nombre del sistema
- Período del reporte
- Tabla con los datos detallados
- Resumen con totales y estadísticas
- Fecha y hora de generación

---

## 8. ADMINISTRACIÓN

### 8.1 Gestión de Roles

#### Acceder
1. Menú lateral → Administración → **"Gestión de Roles"**

#### Crear Rol
1. Hacer clic en **"Nuevo Rol"**
2. Definir:
   - **Nombre del Rol**: Administrador, Operador, etc.
   - **Permisos**: Seleccionar qué puede hacer este rol
3. Hacer clic en **"Guardar"**

#### Asignar Roles a Usuarios
1. En la lista de usuarios
2. Seleccionar el usuario
3. Asignar el rol correspondiente
4. Guardar cambios

---

### 8.2 Configuración del Sistema

#### Acceder
1. Menú lateral → Administración → **"Configuración del Sistema"**

#### Opciones de Configuración
- **Datos de la Empresa**: Nombre, dirección, teléfono, logo
- **Parámetros del Sistema**:
  - Stock mínimo por defecto
  - Días para alertas de vencimiento
  - Formato de fechas
- **Respaldo de Datos**: Generar backup de la base de datos
- **Usuarios**: Gestionar cuentas de usuario

---

## 9. CERRAR SESIÓN

### 9.1 Proceso de Cierre
1. En la barra superior, hacer clic en el nombre del usuario
2. Seleccionar **"Cerrar Sesión"**
3. El sistema cierra la sesión actual
4. Redirige a la pantalla de Login
5. Para volver a entrar, debe iniciar sesión nuevamente

### 9.2 Cierre Automático
- El sistema cierra sesión automáticamente después de un período de inactividad
- Al detectar inactividad, muestra mensaje de advertencia
- Si no hay respuesta, cierra la sesión por seguridad

---

## 10. FLUJO DE TRABAJO DIARIO TÍPICO

### Inicio del Día
1. Iniciar sesión en el sistema
2. Revisar el Dashboard:
   - Verificar alertas de stock bajo
   - Revisar mantenimientos pendientes
   - Ver producción del día anterior

### Durante el Día

#### Producción
1. Ir a "Productos Producidos Diarios"
2. Registrar la producción de cada turno
3. El inventario se actualiza automáticamente

#### Despachos
1. Ir a "Salidas Productos Diarios"
2. Registrar cada salida con:
   - Distribuidor asignado
   - Productos entregados
   - Retornos recibidos
3. El inventario se ajusta automáticamente

#### Controles
- Registrar mantenimientos realizados
- Documentar limpiezas de tanques
- Anotar recepción de insumos

### Fin del Día
1. Verificar que todos los registros estén completos
2. Generar reportes necesarios:
   - Reporte de salidas del día
   - Reporte de producción
3. Revisar el inventario actualizado
4. Cerrar sesión

---

## 11. RECOMENDACIONES DE USO

### Seguridad
- No compartir credenciales de acceso
- Cambiar contraseña periódicamente
- Cerrar sesión al terminar

### Datos
- Registrar información inmediatamente después de cada operación
- Verificar datos antes de guardar
- Usar observaciones para detalles importantes

### Reportes
- Generar reportes periódicamente (diario, semanal, mensual)
- Guardar copias de reportes importantes
- Revisar totales para detectar errores

### Mantenimiento
- Reportar cualquier error del sistema
- Mantener actualizada la información de personal y vehículos
- Realizar respaldos periódicos

---

## 12. SOPORTE TÉCNICO

En caso de problemas con el sistema:
1. Verificar conexión a internet
2. Limpiar caché del navegador
3. Intentar con otro navegador
4. Contactar al administrador del sistema

---

**Versión del Manual**: 1.0
**Última Actualización**: Noviembre 2025
**Sistema**: Agua Colegial - Sistema de Gestión Integral

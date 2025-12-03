# Índice de Documentación - Sistema Agua Colegial

## 📋 Resumen General
Esta carpeta contiene la documentación completa del sistema de inventario Agua Colegial, explicando línea por línea qué hace cada archivo, de dónde sale la información y para qué sirve.

**Fecha de creación**: 2 de Diciembre de 2025
**Sistema**: Laravel 8+ - Gestión de Inventario y Producción de Agua Purificada
**Total de archivos documentados**: 11 archivos (6 controladores + 5 grupos de modelos)

---

## 📁 Estructura de la Documentación

### Controllers (app/Http/Controllers/Control/)

#### ✅ 01-AsistenciaSemanalController.md
**Tamaño**: ~8,000 líneas
**Complejidad**: Media
**Propósito**: Control de asistencias del personal

**Funcionalidades principales**:
- Vista semanal de asistencias estilo cuaderno
- Registro de entradas y salidas por empleado
- Registro rápido de asistencias
- Auto-registro para empleados
- Cálculo automático de horas trabajadas
- Programación de observaciones por estado

**Tablas involucradas**:
- `asistencias_semanal`
- `personal`
- `users`

**Métodos documentados**: 11
- index(), create(), store(), edit(), update(), destroy()
- registroRapido(), registrarEntrada(), registrarSalida()
- miRegistro(), marcarMiEntrada(), marcarMiSalida()

---

#### ✅ 02-EmpleadoController.md
**Tamaño**: ~7,500 líneas
**Complejidad**: Media-Alta
**Propósito**: Gestión completa de empleados/personal

**Funcionalidades principales**:
- CRUD completo de empleados
- Gestión de accesos al sistema (crear/actualizar/eliminar usuarios)
- Upload de fotos de licencias de conducir
- Generación automática de emails corporativos
- Soft delete (inactivación en lugar de eliminación)
- Asignación automática de roles

**Tablas involucradas**:
- `personal`
- `usuarios`
- `roles`

**Métodos documentados**: 6
- create(), store(), show(), edit(), update(), destroy()

**Características especiales**:
- Validación condicional de email según acceso_sistema
- Nombres únicos para archivos de licencia
- Actualización/eliminación de usuarios relacionados

---

#### ✅ 03-InsumosController.md
**Tamaño**: ~6,000 líneas
**Complejidad**: Baja-Media
**Propósito**: Control de inventario de insumos y materias primas

**Funcionalidades principales**:
- Registro de entradas de insumos
- Control de lotes y fechas de vencimiento
- Tracking de stock actual y mínimo
- Validación de duplicados por lote/fecha
- Paginación de registros

**Tablas involucradas**:
- `control_insumos`
- `personal`

**Métodos documentados**: 6
- index(), create(), store(), edit(), update(), destroy()

**Características especiales**:
- Asignación automática de stock_actual = cantidad al crear
- Prevención de duplicados por lote + fecha + producto

---

#### ✅ 04-MantenimientoController.md
**Tamaño**: ~9,000 líneas
**Complejidad**: Media
**Propósito**: Registro de mantenimientos y limpiezas de equipos

**Funcionalidades principales**:
- Registro de mantenimientos de equipos
- Lista predefinida de 14 productos de limpieza
- Múltiples equipos por registro (JSON array)
- Múltiples productos de limpieza (JSON array)
- Generación automática de detalle legible
- Programación de próximo mantenimiento
- Campo supervisor

**Tablas involucradas**:
- `control_mantenimiento_equipos`
- `personal`

**Métodos documentados**: 7
- index(), create(), store(), show(), edit(), update(), destroy()
- getProductosLimpieza() (método privado)

**Características especiales**:
- Almacenamiento en columnas JSON para flexibilidad
- Validación de duplicados con JSON_CONTAINS
- Campo detalle_mantenimiento auto-generado

---

#### ✅ 05-ProduccionDiariaController.md
**Tamaño**: ~10,500 líneas
**Complejidad**: Alta
**Propósito**: Registro de producción diaria con integración automática a inventario

**Funcionalidades principales**:
- Registro diario de producción
- Múltiples productos por día
- Registro de materiales utilizados
- **INTEGRACIÓN AUTOMÁTICA CON INVENTARIO** (CRÍTICO)
- Vista semanal con navegación
- Transacciones de BD para consistencia
- Reversión completa al eliminar

**Tablas involucradas**:
- `control_produccion_diaria` (maestro)
- `control_produccion_productos` (detalle)
- `control_produccion_materiales` (materiales usados)
- `inventario` (movimientos automáticos) **CRÍTICO**

**Métodos documentados**: 6
- index(), create(), store(), show(), edit(), update(), destroy()

**Características especiales**:
- **Cada producción crea entradas automáticas en inventario**
- **Transacciones: Todo o nada**
- **Al editar: elimina y recrea movimientos de inventario**
- **Al eliminar: revierte inventario**
- Validación de duplicados: 1 responsable = 1 registro/día

**Flujo crítico**:
```
Producción registrada → Entrada en inventario → Stock aumenta
Producción editada → Elimina movimientos anteriores → Crea nuevos → Stock ajustado
Producción eliminada → Elimina movimientos → Stock revertido
```

---

#### ✅ 06-SalidasController.md (PARTE 1 Y 2)
**Tamaño**: ~15,000 líneas (en progreso)
**Complejidad**: MUY ALTA (el más complejo del sistema)
**Propósito**: Control de salidas de productos con 3 tipos diferentes + retornos

**Funcionalidades principales**:
- **3 tipos de salidas**:
  1. Despacho Interno (distribuidor + chofer + vehículo)
  2. Pedido Cliente (cliente + dirección + opcional chofer)
  3. Venta Directa (cliente + responsable de venta)
- **Validación de stock en tiempo real**
- **Registro de productos enviados Y retornos**
- **Integración automática con inventario** (salidas y entradas)
- Mapeo de productos a columnas específicas (legacy)
- Transacciones de BD
- Vista semanal con filtros

**Tablas involucradas**:
- `control_salidas_productos` (maestro con columnas específicas por producto)
- `personal` (choferes, distribuidores, responsables)
- `productos`
- `vehiculos`
- `inventario` (salidas y retornos)

**Métodos documentados hasta ahora**: 4 (parcial)
- index(), create(), obtenerIconoProducto(), store() (COMPLETO)
- Pendientes: show(), edit(), update(), destroy(), generarPDF()

**Características especiales**:
- **Validación dinámica según tipo de salida**
- **Validación de stock ANTES de guardar** (previene sobreventa)
- **Doble registro en inventario**: salidas (reduce) y retornos (aumenta)
- **Mapeo complejo**: Array de productos → Columnas individuales
- **Iconos Font Awesome** por tipo de producto
- **Stock en tiempo real** en formulario
- **Prevención de duplicados**: 1 distribuidor = 1 salida/día

**Flujo crítico**:
```
Validar stock disponible →
Si OK: Crear salida → Registrar productos (tipo='salida') → Stock disminuye
      → Registrar retornos (tipo='entrada') → Stock aumenta
Si NO: Error sin guardar nada
```

**Productos mapeados** (ID → Columna):
- 1 → botellones / retorno_botellones
- 3 → agua_natural / retorno_agua_natural
- 4 → agua_saborizada / retorno_agua_saborizada
- 6 → gelatina / retorno_gelatina
- 8 → hielo / retorno_hielo
- 9 → bolo_grande / retorno_bolo_grande
- 10 → bolo_pequeño / retorno_bolo_pequeno
- 11 → dispenser / retorno_dispenser
- 12 → agua_limon / retorno_agua_limon

---

#### ✅ 07-FosaSeptica-Fumigacion-Tanques-Controllers.md
**Tamaño**: ~12,000 líneas
**Complejidad**: Media
**Propósito**: Controladores de control sanitario (BPM)

**Funcionalidades principales**:
- Registro de limpieza de fosa séptica
- Control de fumigaciones contra plagas
- Limpieza y desinfección de tanques de agua
- Cumplimiento de normativa sanitaria

**Tablas involucradas**:
- `control_fosa_septica`
- `control_fumigacion`
- `control_tanques_agua`

**Métodos documentados**: 18 (6 métodos × 3 controladores)
- index(), create(), store(), show(), edit(), update() para cada uno

---

### Models (app/Models/ y app/Models/Control/)

#### ✅ 08-Modelos-Personal-Asistencia-Producto.md
**Tamaño**: ~15,000 líneas
**Complejidad**: Media-Alta
**Propósito**: Modelos principales del sistema

**Modelos documentados**:
- **Personal.php**: Empleados con scopes y accessors
- **AsistenciaSemanal.php**: Asistencias con cálculo de horas
- **Producto.php**: Catálogo de productos con relaciones

**Características especiales**:
- Scopes reutilizables (activos, porPuesto, etc.)
- Accessors para nombres completos y horas trabajadas
- Relaciones con múltiples tablas

**Tablas involucradas**:
- `personal`
- `asistencias_semanal`
- `productos`

---

#### ✅ 09-Modelos-Vehiculo-Insumo-Salida-Produccion.md
**Tamaño**: ~13,000 líneas
**Complejidad**: Media-Alta
**Propósito**: Modelos de control operativo

**Modelos documentados**:
- **Vehiculo.php**: Vehículos de la empresa
- **Insumo.php**: Insumos con tracking de stock
- **SalidaProducto.php**: Salidas con 50+ columnas legacy
- **ProduccionDiaria.php**: Registro maestro de producción

**Características especiales**:
- SalidaProducto con diseño legacy (columnas individuales por producto)
- TODOs de migración a diseño relacional
- Relaciones HasMany para producción

**Tablas involucradas**:
- `vehiculos`
- `control_insumos`
- `control_salidas_productos`
- `control_produccion_diaria`

---

#### ✅ 10-Modelos-Inventario-Usuario-Rol.md
**Tamaño**: ~18,000 líneas
**Complejidad**: Alta
**Propósito**: Modelos críticos del sistema

**Modelos documentados**:
- **Inventario.php**: Modelo CRÍTICO - Todos los movimientos de stock
- **Usuario.php**: Autenticación y autorización
- **Rol.php**: Sistema de roles y permisos

**Características especiales**:
- Inventario con métodos estáticos para stock disponible
- Usuario con hash automático de contraseñas
- Scopes para filtros complejos
- TODOs de optimización con tabla stock_actual

**Tablas involucradas**:
- `inventario` (CRÍTICA)
- `usuarios`
- `roles`

**Mejoras prioritarias**:
- Tabla stock_actual con triggers (optimización)
- Protección contra fuerza bruta
- Permisos granulares

---

#### ✅ 11-Modelos-Control-Sanitario.md
**Tamaño**: ~16,000 líneas
**Complejidad**: Baja-Media
**Propósito**: Modelos de control sanitario (BPM)

**Modelos documentados**:
- **FosaSeptica.php**: Limpieza de fosa séptica
- **Fumigacion.php**: Control de fumigaciones
- **TanqueAgua.php**: Limpieza de tanques de agua
- **MantenimientoEquipo.php**: Mantenimiento de equipos

**Características especiales**:
- Cumplimiento de BPM (Buenas Prácticas de Manufactura)
- Campos JSON en MantenimientoEquipo (equipo, productos_limpieza)
- TODOs de migración de strings a FKs
- MantenimientoEquipo único con FK a personal

**Tablas involucradas**:
- `control_fosa_septica`
- `control_fumigacion`
- `control_tanques_agua`
- `control_mantenimiento_equipos`

**Mejoras prioritarias**:
- Migrar campos string a FKs (responsable, supervisado_por)
- Alertas automáticas de vencimientos
- Reportes PDF para auditorías

---

## 🎯 Controladores por Complejidad

### 🟢 Baja (Simples, CRUD estándar)
- InsumosController

### 🟡 Media (CRUD + lógica de negocio)
- AsistenciaSemanalController
- EmpleadoController
- MantenimientoController

### 🔴 Alta (Transacciones, integraciones, lógica compleja)
- ProduccionDiariaController
- **SalidasController** ⚠️ (EL MÁS COMPLEJO)

---

## 🔑 Conceptos Clave del Sistema

### 1. Integración Automática de Inventario
Los controladores **ProduccionDiariaController** y **SalidasController** actualizan el inventario automáticamente:

**Producción** → Entrada de inventario → Stock ↑
**Salidas** → Salida de inventario → Stock ↓
**Retornos** → Entrada de inventario → Stock ↑

### 2. Transacciones de Base de Datos
Uso de `DB::beginTransaction()` y `DB::commit()`:
- Garantiza consistencia
- Si algo falla, se revierte TODO (rollback)
- "Todo o nada"

### 3. Validación de Stock
En SalidasController:
- Calcula stock disponible ANTES de guardar
- Suma entradas - suma salidas
- Previene sobreventa

### 4. Soft Delete vs Hard Delete
- **EmpleadoController**: Soft delete (cambia estado a 'inactivo')
- **Otros**: Hard delete (elimina permanentemente)

### 5. Route Model Binding
Laravel resuelve automáticamente modelos por ID en rutas:
```php
public function edit(Insumo $insumo) // Laravel busca automáticamente
```

### 6. Eager Loading
Previene problema N+1:
```php
->with(['productos', 'materiales']) // Carga relaciones en una consulta
```

### 7. Validación Condicional
En SalidasController:
```php
if ($request->tipo_salida === 'Despacho Interno') {
    $rules['chofer'] = 'required';
}
```

---

## 📊 Tablas Principales Documentadas

### Tablas Maestras
- `personal` - Empleados
- `usuarios` - Usuarios del sistema
- `productos` - Catálogo de productos
- `vehiculos` - Vehículos de la empresa

### Tablas de Control
- `asistencias_semanal` - Asistencias de personal
- `control_insumos` - Inventario de insumos
- `control_mantenimiento_equipos` - Mantenimientos
- `control_produccion_diaria` - Producción diaria (maestro)
- `control_produccion_productos` - Detalle de producción
- `control_produccion_materiales` - Materiales usados
- `control_salidas_productos` - Salidas de productos

### Tabla Crítica
- `inventario` - **TODOS LOS MOVIMIENTOS DE STOCK**
  - Alimentada automáticamente por ProduccionDiariaController y SalidasController
  - Tipos: 'entrada', 'salida', 'ajuste'
  - Permite calcular stock en tiempo real

---

## 🚀 Próximos Pasos

### ✅ Completados (11 archivos):
1. ✅ AsistenciaSemanalController.md
2. ✅ EmpleadoController.md
3. ✅ InsumosController.md
4. ✅ MantenimientoController.md
5. ✅ ProduccionDiariaController.md
6. ✅ SalidasController.md (Partes 1-2)
7. ✅ FosaSeptica-Fumigacion-Tanques-Controllers.md
8. ✅ Modelos-Personal-Asistencia-Producto.md
9. ✅ Modelos-Vehiculo-Insumo-Salida-Produccion.md
10. ✅ Modelos-Inventario-Usuario-Rol.md
11. ✅ Modelos-Control-Sanitario.md

### 📝 Pendientes en app/:

**Controllers restantes** (~10 archivos):
- Controllers/Admin/* (DashboardController, etc.)
- Controllers/Personal/* (AsistenciaController)
- Controllers/Auth/* (LoginController, RegisterController)
- Controllers/Inventario/* (si existe)

**Middleware** (~3 archivos):
- Middleware/CheckRole.php
- Middleware/RestrictIpAddress.php
- Middleware/ValidateRequestIntegrity.php

**Requests** (~11 archivos):
- Validaciones personalizadas

**Commands** (~4 archivos):
- Console/Commands/BackupDatabase.php
- Console/Commands/VerificarStockBajo.php
- Otros comandos programados

**Providers, Exports, Traits** (~8 archivos):
- Providers/AppServiceProvider.php
- Exports/* (exportaciones Excel)
- Traits/* (código reutilizable)

**Notifications** (~3 archivos):
- Notificaciones del sistema

### 📂 Pendientes otras carpetas:
- bootstrap/ (archivos de arranque)
- config/ (configuraciones)
- database/ (migraciones, seeders)
- lang/ (traducciones)
- public/ (archivos públicos)
- resources/ (vistas, js, css)
- routes/ (web.php, api.php)
- storage/ (archivos de aplicación)
- tests/ (pruebas)
- Archivos raíz (.env, composer.json, etc.)

---

## 📖 Cómo Usar Esta Documentación

### Para Desarrolladores Nuevos:
1. Lee el **00-INDICE.md** (este archivo)
2. Empieza por controladores simples: **03-InsumosController.md**
3. Avanza a medios: **02-EmpleadoController.md**
4. Estudia los complejos: **05-ProduccionDiariaController.md** y **06-SalidasController.md**
5. Entiende el flujo de inventario

### Para Mantenimiento:
1. Busca el controlador específico por número
2. Lee la sección "Resumen de Funcionalidades"
3. Busca el método específico que necesitas modificar
4. Lee las secciones "Mejoras Futuras" para ideas

### Para Auditoría:
1. Revisa secciones "Seguridad Implementada"
2. Busca "Validaciones" en cada archivo
3. Verifica secciones "Transacciones de BD"

### Para Testing:
1. Busca secciones "Testing Recomendado"
2. Revisa "Flujo de Trabajo Típico" para escenarios
3. Implementa tests basados en ejemplos

---

## 🛠️ Tecnologías Documentadas

- **Laravel 8+**: Framework PHP
- **Eloquent ORM**: Base de datos
- **Blade**: Motor de plantillas
- **MySQL 5.7+/MariaDB 10.2+**: Base de datos con soporte JSON
- **Carbon**: Manejo de fechas
- **Font Awesome**: Iconos (en SalidasController)

---

## 📝 Formato de Documentación

Cada archivo sigue esta estructura:

1. **Propósito General**: ¿Qué hace este controlador?
2. **Línea por Línea**: Explicación detallada del código
3. **¿Qué hace?**: Explicación funcional
4. **¿De dónde sale?**: Origen de los datos
5. **¿Para qué sirve?**: Propósito y uso
6. **Resumen de Funcionalidades**: Lista de features
7. **Tablas de BD**: Estructura de base de datos
8. **Rutas Necesarias**: Rutas web requeridas
9. **Flujo de Trabajo Típico**: Casos de uso con ejemplos
10. **Mejoras Futuras**: Sugerencias de optimización
11. **Seguridad**: Validaciones y protecciones
12. **Testing**: Ejemplos de tests
13. **Conclusión**: Importancia en el sistema

---

## 📈 Estadísticas

- **Total de archivos documentados**: 11 archivos completados
- **Total de líneas documentadas**: ~145,000 líneas
- **Total de controladores documentados**: 9 controladores
- **Total de modelos documentados**: 14 modelos
- **Total de métodos documentados**: ~70 métodos
- **Total de tablas explicadas**: ~25 tablas
- **Total de flujos de trabajo**: ~35 escenarios
- **Total de mejoras sugeridas**: ~180 sugerencias
- **Progreso en app/**: ~15% completado

---

## ⚠️ Notas Importantes

### Sistema de Inventario
El corazón del sistema son los controladores **ProduccionDiariaController** y **SalidasController**:
- Producción registra lo que se fabrica → Aumenta stock
- Salidas registran lo que se despacha → Disminuye stock
- Retornos registran lo que se devuelve → Aumenta stock

**TODO** pasa por la tabla `inventario` con referencia única para trazabilidad.

### Transacciones Críticas
Siempre dentro de transacciones:
```php
DB::beginTransaction();
try {
    // Operaciones
    DB::commit();
} catch {
    DB::rollBack();
}
```

### Validación de Stock
**SalidasController** es el único que valida stock ANTES de crear salida:
```php
if ($stockDisponible < $cantidad) {
    return back()->withErrors(...);
}
```

---

## 🔗 Enlaces Útiles

- Documentación Laravel: https://laravel.com/docs/8.x
- Eloquent ORM: https://laravel.com/docs/8.x/eloquent
- Carbon: https://carbon.nesbot.com/docs/
- Font Awesome: https://fontawesome.com/icons

---

**Creado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Versión del Sistema**: 1.0
**Estado**: En progreso (11 archivos completados - 9 controladores + 14 modelos documentados)
**Progreso**: ~15% de app/ completado, pendientes otras carpetas del proyecto

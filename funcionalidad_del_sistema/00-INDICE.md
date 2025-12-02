# Índice de Documentación - Sistema Agua Colegial

## 📋 Resumen General
Esta carpeta contiene la documentación completa del sistema de inventario Agua Colegial, explicando línea por línea qué hace cada archivo, de dónde sale la información y para qué sirve.

**Fecha de creación**: 2 de Diciembre de 2025
**Sistema**: Laravel 8+ - Gestión de Inventario y Producción de Agua Purificada
**Total de archivos documentados**: 6 controladores principales

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

### Pendientes de Documentar:
1. **SalidasController** - Completar Parte 3, 4 y 5:
   - show(), edit(), update(), destroy(), generarPDF()
   - Resumen completo
   - Flujos de trabajo detallados
   - Mejoras sugeridas

2. **Models** (app/Models/):
   - Personal.php
   - AsistenciaSemanal.php
   - Producto.php
   - Vehiculo.php
   - Control/Insumo.php
   - Control/MantenimientoEquipo.php
   - Control/ProduccionDiaria.php
   - Control/SalidaProducto.php
   - Inventario.php

3. **Otros Controladores** (si aplica):
   - Controllers de Admin/
   - Controllers de Produccion/
   - Controllers de Personal/

4. **Middleware, Requests, Traits**:
   - Middleware/CheckRole.php
   - Requests/ValidacionesPersonalizadas
   - Traits/DataIntegrity.php

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

- **Total de líneas documentadas**: ~56,000 líneas
- **Total de métodos documentados**: ~40 métodos
- **Total de tablas explicadas**: ~15 tablas
- **Total de flujos de trabajo**: ~20 escenarios
- **Total de mejoras sugeridas**: ~100 sugerencias

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
**Estado**: En progreso (6 de ~20 archivos documentados)

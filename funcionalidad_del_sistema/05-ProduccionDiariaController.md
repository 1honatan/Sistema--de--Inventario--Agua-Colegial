# ProduccionDiariaController - Documentación Detallada

**Ubicación**: `app/Http/Controllers/Control/ProduccionDiariaController.php`

## Propósito General
Este controlador gestiona el registro diario de producción de agua y productos derivados. Es uno de los controladores más críticos del sistema porque registra lo producido cada día y AUTOMÁTICAMENTE actualiza el inventario general, creando entradas en la tabla de movimientos de inventario.

---

## Línea por Línea

### Líneas 1-13: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\ProduccionDiaria;
use App\Models\Producto;
use App\Models\Personal;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
```

**¿Qué hace?**
- **Línea 3**: Namespace en carpeta Control
- **Línea 5**: Controller base
- **Línea 6**: Modelo ProduccionDiaria (registro principal)
- **Línea 7**: Modelo Producto (catálogo de productos)
- **Línea 8**: Modelo Personal (empleados)
- **Línea 9**: Modelo Inventario (movimientos de stock) **MUY IMPORTANTE**
- **Línea 11**: Auth facade para obtener usuario autenticado
- **Línea 12**: DB facade para transacciones

**¿De dónde sale?**
- Laravel framework y modelos del sistema

**¿Para qué sirve?**
- **Auth**: Registrar quién hizo el movimiento de inventario
- **DB**: Garantizar consistencia con transacciones (todo o nada)

---

### Líneas 16-30: Método `index()` - Listar producción por semana
```php
public function index(Request $request)
{
    // Líneas 18-19: Obtener semana solicitada
    $semana = (int) $request->get('semana', 0); // 0 = semana actual, -1 = anterior, 1 = siguiente

    // Líneas 21-22: Calcular inicio y fin de semana
    $inicioSemana = now()->addWeeks($semana)->startOfWeek(); // Lunes
    $finSemana = now()->addWeeks($semana)->endOfWeek(); // Domingo
```

**¿Qué hace?**
- **Línea 19**: Lee parámetro 'semana' de la URL
  - `0`: Semana actual
  - `-1`: Semana anterior
  - `1`: Semana siguiente
  - Cast a int para seguridad
- **Línea 21**: Calcula lunes de la semana solicitada
  - `now()`: Fecha actual
  - `->addWeeks($semana)`: Suma/resta semanas
  - `->startOfWeek()`: Primer día (lunes)
- **Línea 22**: Calcula domingo de esa semana

**¿De dónde sale?**
- URL: `?semana=0` (actual), `?semana=-1` (anterior), `?semana=1` (siguiente)
- Carbon para manejo de fechas

**Ejemplo**:
- Hoy: 2025-12-02 (martes)
- `semana=0`: Lunes 2025-11-26 a Domingo 2025-12-01
- `semana=-1`: Lunes 2025-11-19 a Domingo 2025-11-25
- `semana=1`: Lunes 2025-12-03 a Domingo 2025-12-09

```php
    // Líneas 24-27: Obtener producciones de la semana
    $producciones = ProduccionDiaria::with(['productos', 'materiales'])
        ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
        ->orderBy('fecha', 'desc')
        ->get();

    return view('control.produccion.index', compact('producciones', 'inicioSemana', 'finSemana', 'semana'));
}
```

**¿Qué hace?**
- **Línea 24**: Query con eager loading
  - `.with(['productos', 'materiales'])`: Carga relaciones (evita N+1)
- **Línea 25**: Filtra por rango de fechas
  - `whereBetween`: SQL BETWEEN
- **Línea 26**: Ordena descendente (más recientes primero)
- **Línea 27**: Ejecuta query

**¿De dónde sale?**
- Tabla `control_produccion_diaria`
- Relaciones:
  - `productos()`: Tabla `control_produccion_productos` (productos producidos)
  - `materiales()`: Tabla `control_produccion_materiales` (materiales usados)

**¿Para qué sirve?**
- Vista semanal estilo calendario
- Navegación entre semanas con botones anterior/siguiente
- Ver resumen de producción por día

---

### Líneas 32-46: Método `create()` - Formulario de registro
```php
public function create()
{
    // Líneas 34-37: Obtener productos activos
    $productos = Producto::where('estado', 'activo')
        ->orderBy('nombre')
        ->get();

    // Líneas 39-43: Obtener personal (sin choferes, distribuidores ni supervisores)
    $personal = Personal::where('estado', 'activo')
        ->whereNotIn('cargo', ['Chofer', 'Distribuidor', 'Supervisor'])
        ->orderBy('nombre_completo')
        ->get();

    return view('control.produccion.create', compact('productos', 'personal'));
}
```

**¿Qué hace?**
- **Líneas 35-37**: Obtiene productos activos ordenados
  - Solo productos con `estado='activo'`
- **Líneas 40-42**: Obtiene personal de producción
  - **Excluye** choferes, distribuidores y supervisores
  - Solo operadores, encargados de producción, etc.

**¿De dónde sale?**
- Tabla `productos`
- Tabla `personal`

**¿Para qué sirve?**
- Select de productos para indicar qué se produjo
- Select de responsable de la producción
- Choferes/distribuidores no registran producción (solo despachan)

---

### Líneas 48-139: Método `store()` - Guardar producción (MUY IMPORTANTE)
```php
public function store(Request $request)
{
    // Líneas 50-61: Validación de datos
    $validated = $request->validate([
        'fecha' => 'required|date',
        'responsable' => 'required|string',
        'gasto_material' => 'nullable|numeric|min:0',
        'observaciones' => 'nullable|string',
        'productos' => 'required|array|min:1',
        'productos.*.producto' => 'required|string',
        'productos.*.cantidad' => 'required|numeric|min:1',
        'materiales' => 'nullable|array',
        'materiales.*.material' => 'nullable|string',
        'materiales.*.cantidad' => 'nullable|numeric|min:0',
    ]);
```

**¿Qué hace cada validación?**
- **fecha**: Requerido, fecha de producción
- **responsable**: Requerido, nombre del encargado
- **gasto_material**: Opcional, costo en materiales (números decimales, no negativo)
- **observaciones**: Opcional, notas
- **productos**: Requerido, array con al menos 1 producto
  - **productos.\*.producto**: Nombre del producto (string)
  - **productos.\*.cantidad**: Cantidad producida (número >= 1)
- **materiales**: Opcional, array de materiales usados
  - **materiales.\*.material**: Nombre del material
  - **materiales.\*.cantidad**: Cantidad usada (número >= 0)

**Estructura de datos esperada**:
```javascript
{
    "fecha": "2025-12-02",
    "responsable": "Juan Pérez",
    "gasto_material": 150.50,
    "observaciones": "Producción normal",
    "productos": [
        {"producto": "Botellón 20L", "cantidad": 500},
        {"producto": "Bolo Grande", "cantidad": 200}
    ],
    "materiales": [
        {"material": "Cloro", "cantidad": 5},
        {"material": "Etiquetas", "cantidad": 500}
    ]
}
```

```php
    // Líneas 63-72: Validación de duplicados
    $existeDuplicado = ProduccionDiaria::where('fecha', $validated['fecha'])
        ->where('responsable', $validated['responsable'])
        ->exists();

    if ($existeDuplicado) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Ya existe un registro de producción para ' . $validated['responsable'] . ' en la fecha ' . date('d/m/Y', strtotime($validated['fecha'])) . '. Por favor, verifique los registros existentes o edite el registro anterior.']);
    }
```

**¿Qué hace?**
- Busca si ya existe producción del mismo responsable en la misma fecha
- Si existe, retorna error

**¿Para qué sirve?**
- Evitar registros duplicados
- Un responsable solo debe registrar producción una vez por día
- Sugerir editar el registro existente

```php
    // Líneas 74-75: Iniciar transacción
    DB::beginTransaction();

    try {
```

**¿Qué hace?**
- **beginTransaction()**: Inicia transacción de base de datos
- Todo lo que sigue se ejecutará como una unidad atómica
- Si algo falla, se revierte TODO (rollback)

**¿De dónde sale?**
- Sistema de transacciones de MySQL/PostgreSQL

**¿Para qué sirve?**
- Garantizar consistencia de datos
- Si falla registro en inventario, también se revierte la producción
- "Todo o nada"

```php
        // Líneas 78-87: Crear registro principal de producción
        $produccion = ProduccionDiaria::create([
            'fecha' => $validated['fecha'],
            'responsable' => $validated['responsable'],
            'turno' => null,
            'preparacion' => null,
            'rollos_material' => 0,
            'gasto_material' => $validated['gasto_material'] ?? 0,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);
```

**¿Qué hace?**
- Crea registro en tabla `control_produccion_diaria`
- Campos importantes:
  - **turno**: null (campo legacy, no se usa)
  - **preparacion**: null (campo legacy)
  - **rollos_material**: 0 (campo legacy para rollos de material de empaque)
  - **gasto_material**: costo o 0 si no se especificó
  - Operador `??`: valor por defecto si es null

**¿De dónde sale?**
- Datos validados del formulario

**¿Para qué sirve?**
- Registro maestro de la producción
- Se relaciona con productos y materiales

```php
        // Líneas 89-113: Guardar productos Y registrar en inventario
        foreach ($validated['productos'] as $productoData) {
            // Línea 92: Buscar producto por nombre
            $producto = Producto::where('nombre', $productoData['producto'])->first();

            if ($producto) {
                // Líneas 96-99: Guardar en tabla de producción
                $produccion->productos()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                ]);

                // Líneas 101-111: Registrar ENTRADA en inventario general
                Inventario::create([
                    'id_producto' => $producto->id,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $productoData['cantidad'],
                    'origen' => 'Producción Diaria',
                    'referencia' => 'Producción #' . $produccion->id,
                    'id_usuario' => Auth::id(),
                    'fecha_movimiento' => $validated['fecha'],
                    'observacion' => 'Entrada automática desde Control de Producción Diaria - Responsable: ' . $validated['responsable'],
                ]);
            }
        }
```

**¿Qué hace? (CRÍTICO)**
- **Línea 90**: Recorre cada producto producido
- **Línea 92**: Busca el producto en catálogo por nombre
- **Líneas 96-99**: Guarda relación en `control_produccion_productos`
  - Tabla intermedia: produccion_diaria_id + producto_id + cantidad
- **Líneas 102-111**: **AUTOMÁTICAMENTE** crea movimiento de inventario
  - **tipo_movimiento**: 'entrada' (aumenta stock)
  - **origen**: 'Producción Diaria'
  - **referencia**: 'Producción #X' (para rastrear)
  - **id_usuario**: Quién registró (usuario logueado)
  - **fecha_movimiento**: Fecha de producción (no de registro)

**¿De dónde sale?**
- `Auth::id()`: ID del usuario autenticado actual

**¿Para qué sirve?**
- Doble propósito:
  1. Guardar detalle de qué se produjo
  2. **Actualizar inventario automáticamente**
- El inventario siempre está sincronizado con la producción

**Ejemplo**:
- Se producen 500 Botellones 20L
- Registro en `control_produccion_productos`: produccion_id=1, producto_id=1, cantidad=500
- Registro en `inventario`: producto_id=1, tipo='entrada', cantidad=500
- El stock de Botellones 20L aumenta en 500 unidades

```php
        // Líneas 115-125: Guardar materiales utilizados
        if (isset($validated['materiales'])) {
            foreach ($validated['materiales'] as $materialData) {
                if (!empty($materialData['material'])) {
                    $produccion->materiales()->create([
                        'nombre_material' => $materialData['material'],
                        'cantidad' => $materialData['cantidad'] ?? 0,
                    ]);
                }
            }
        }
```

**¿Qué hace?**
- Si se especificaron materiales usados, los guarda
- **Línea 118**: Verifica que el nombre del material no esté vacío
- Guarda en tabla `control_produccion_materiales`
- Campo `nombre_material`: texto libre (no FK)

**¿De dónde sale?**
- Array opcional de materiales del formulario

**¿Para qué sirve?**
- Rastrear qué insumos se usaron
- Ejemplo: 5 kg de Cloro, 500 Etiquetas, 200 Tapas
- **Nota**: No descuenta del inventario de insumos (mejora pendiente)

```php
        DB::commit();

        return redirect()->route('control.produccion.index')
            ->with('success', 'Registro de producción creado exitosamente y productos agregados al inventario.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al guardar la producción: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- **Línea 127**: Commit - confirma todos los cambios
  - Si llegó aquí, todo salió bien
- **Líneas 129-130**: Redirige con mensaje de éxito
- **Líneas 132-137**: Catch - si hubo error
  - **rollBack()**: Revierte TODOS los cambios
  - Retorna al formulario con datos y mensaje de error

**¿De dónde sale?**
- Patrón try-catch de PHP
- Sistema de transacciones de Laravel

**¿Para qué sirve?**
- Garantizar atomicidad
- Si falla inventario, también se cancela la producción
- Base de datos queda consistente

---

### Líneas 141-145: Método `show()` - Ver detalles
```php
public function show(ProduccionDiaria $produccion)
{
    $produccion->load(['productos', 'materiales']);
    return view('control.produccion.show', compact('produccion'));
}
```

**¿Qué hace?**
- **Línea 143**: Lazy loading de relaciones (si no estaban cargadas)
- Muestra vista de detalles

---

### Líneas 147-159: Método `edit()` - Formulario de edición
```php
public function edit(ProduccionDiaria $produccion)
{
    $productos = Producto::orderBy('nombre')->get();

    // Líneas 152-155: Excluir choferes, distribuidores y supervisores
    $personal = Personal::where('estado', 'activo')
        ->whereNotIn('cargo', ['Chofer', 'Distribuidor', 'Supervisor'])
        ->orderBy('nombre_completo')
        ->get();

    $produccion->load(['productos', 'materiales']);
    return view('control.produccion.edit', compact('produccion', 'productos', 'personal'));
}
```

**¿Qué hace?**
- Similar a `create()` pero incluye objeto `$produccion` con datos actuales
- Carga todos los productos (no solo activos) para permitir editar registros antiguos

---

### Líneas 161-245: Método `update()` - Actualizar producción (COMPLEJO)
```php
public function update(Request $request, ProduccionDiaria $produccion)
{
    // Líneas 163-174: Validación (igual que store)
    $validated = $request->validate([
        'fecha' => 'required|date',
        'responsable' => 'required|string',
        'gasto_material' => 'nullable|numeric|min:0',
        'observaciones' => 'nullable|string',
        'productos' => 'required|array|min:1',
        'productos.*.producto' => 'required|string',
        'productos.*.cantidad' => 'required|numeric|min:1',
        'materiales' => 'nullable|array',
        'materiales.*.material' => 'nullable|string',
        'materiales.*.cantidad' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        // Líneas 179-185: Actualizar registro principal
        $produccion->update([
            'fecha' => $validated['fecha'],
            'responsable' => $validated['responsable'],
            'gasto_material' => $validated['gasto_material'] ?? 0,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);
```

**¿Qué hace?**
- Validación idéntica a `store()`
- Actualiza campos del registro principal

```php
        // Líneas 187-188: ELIMINAR entradas de inventario antiguas
        Inventario::where('referencia', 'Producción #' . $produccion->id)->delete();

        // Líneas 190-191: ELIMINAR productos antiguos
        $produccion->productos()->delete();
```

**¿Qué hace? (CRÍTICO)**
- **Línea 188**: Elimina movimientos de inventario previos de esta producción
  - Busca por referencia única: 'Producción #5'
  - **Hard delete**: Elimina permanentemente
- **Línea 191**: Elimina registros de productos antiguos
  - Borra de `control_produccion_productos`

**¿Por qué?**
- Enfoque "eliminar y recrear"
- Más simple que detectar diferencias
- Garantiza sincronización perfecta con inventario

**Riesgo**:
- Si se cambia cantidad de 500 a 600, se elimina entrada de 500 y se crea entrada de 600
- El inventario queda consistente

```php
        // Líneas 193-216: Crear nuevos productos y registrar en inventario
        foreach ($validated['productos'] as $productoData) {
            $producto = Producto::where('nombre', $productoData['producto'])->first();

            if ($producto) {
                // Guardar en la tabla de producción
                $produccion->productos()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                ]);

                // Registrar entrada en el inventario general
                Inventario::create([
                    'id_producto' => $producto->id,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $productoData['cantidad'],
                    'origen' => 'Producción Diaria',
                    'referencia' => 'Producción #' . $produccion->id,
                    'id_usuario' => Auth::id(),
                    'fecha_movimiento' => $validated['fecha'],
                    'observacion' => 'Entrada automática desde Control de Producción Diaria (Editado) - Responsable: ' . $validated['responsable'],
                ]);
            }
        }
```

**¿Qué hace?**
- Idéntico a `store()` pero con observación "(Editado)"
- Crea nuevos registros de productos
- Crea nuevos movimientos de inventario

```php
        // Líneas 218-231: Recrear materiales
        $produccion->materiales()->delete();

        if (isset($validated['materiales'])) {
            foreach ($validated['materiales'] as $materialData) {
                if (!empty($materialData['material'])) {
                    $produccion->materiales()->create([
                        'nombre_material' => $materialData['material'],
                        'cantidad' => $materialData['cantidad'] ?? 0,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('control.produccion.index')
            ->with('success', 'Registro de producción actualizado exitosamente y cambios reflejados en inventario.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar la producción: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Elimina materiales antiguos
- Crea nuevos materiales
- Commit de transacción
- Manejo de errores con rollback

---

### Líneas 247-276: Método `destroy()` - Eliminar producción (REVERTIR INVENTARIO)
```php
public function destroy(ProduccionDiaria $produccion)
{
    DB::beginTransaction();

    try {
        // Línea 253: Revertir entradas de inventario
        Inventario::where('referencia', 'Producción #' . $produccion->id)->delete();

        // Línea 256: Eliminar productos relacionados
        $produccion->productos()->delete();

        // Línea 259: Eliminar materiales relacionados
        $produccion->materiales()->delete();

        // Línea 262: Eliminar el registro de producción
        $produccion->delete();

        DB::commit();

        return redirect()->route('control.produccion.index')
            ->with('success', 'Registro de producción eliminado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->with('error', 'Error al eliminar la producción: ' . $e->getMessage());
    }
}
```

**¿Qué hace? (MUY IMPORTANTE)**
- **Línea 253**: **REVIERTE** el inventario
  - Elimina movimientos de entrada
  - El stock disminuye automáticamente
- **Línea 256**: Elimina detalles de productos
- **Línea 259**: Elimina materiales
- **Línea 262**: Elimina registro maestro
- Todo dentro de transacción

**¿Por qué es importante?**
- Si se borra producción de 500 botellones, el inventario disminuye en 500
- Mantiene consistencia

**Ejemplo**:
- Stock actual: 1000 botellones
- Producción registrada: +500 (total 1500)
- Se elimina producción: -500 (vuelve a 1000)

---

## Resumen de Funcionalidades

### Operaciones CRUD:
1. **Listar producción**: `index()` - Vista semanal con navegación
2. **Crear producción**: `create()` + `store()` - Registrar producción y actualizar inventario
3. **Ver producción**: `show()` - Detalles de un día
4. **Editar producción**: `edit()` + `update()` - Modificar y ajustar inventario
5. **Eliminar producción**: `destroy()` - Borrar y revertir inventario

### Características Especiales:
6. **Integración automática con inventario**: Cada producción genera entradas
7. **Transacciones de BD**: Garantiza consistencia
8. **Validación de duplicados**: Un responsable = un registro por día
9. **Vista semanal**: Navegación por semanas
10. **Productos múltiples**: Un registro puede tener varios productos
11. **Rastreo de materiales**: Registra insumos usados
12. **Auditoría**: Registra quién y cuándo

---

## Tablas de Base de Datos Involucradas

### 1. **control_produccion_diaria** (maestro)
Campos:
- `id`: INT, PK
- `fecha`: DATE
- `responsable`: VARCHAR(255)
- `turno`: VARCHAR(50), nullable (legacy)
- `preparacion`: VARCHAR(255), nullable (legacy)
- `rollos_material`: INT (legacy)
- `gasto_material`: DECIMAL(10,2)
- `observaciones`: TEXT, nullable
- `created_at`, `updated_at`: TIMESTAMP

### 2. **control_produccion_productos** (detalle)
Campos:
- `id`: INT, PK
- `produccion_diaria_id`: INT, FK → control_produccion_diaria.id
- `producto_id`: INT, FK → productos.id
- `cantidad`: DECIMAL(10,2)
- `created_at`, `updated_at`: TIMESTAMP

### 3. **control_produccion_materiales** (materiales usados)
Campos:
- `id`: INT, PK
- `produccion_diaria_id`: INT, FK → control_produccion_diaria.id
- `nombre_material`: VARCHAR(255)
- `cantidad`: DECIMAL(10,2)
- `created_at`, `updated_at`: TIMESTAMP

### 4. **inventario** (movimientos)
Campos:
- `id`: INT, PK
- `id_producto`: INT, FK → productos.id
- `tipo_movimiento`: ENUM('entrada', 'salida', 'ajuste')
- `cantidad`: DECIMAL(10,2)
- `origen`: VARCHAR(255)
- `destino`: VARCHAR(255), nullable
- `referencia`: VARCHAR(255) - **CLAVE PARA RASTREO**
- `id_usuario`: INT, FK → users.id
- `fecha_movimiento`: DATE
- `observacion`: TEXT, nullable
- `created_at`, `updated_at`: TIMESTAMP

---

## Relaciones de Modelos

En el modelo **ProduccionDiaria**:
```php
public function productos()
{
    return $this->hasMany(ProduccionProducto::class, 'produccion_diaria_id');
}

public function materiales()
{
    return $this->hasMany(ProduccionMaterial::class, 'produccion_diaria_id');
}
```

En el modelo **ProduccionProducto**:
```php
public function produccionDiaria()
{
    return $this->belongsTo(ProduccionDiaria::class, 'produccion_diaria_id');
}

public function producto()
{
    return $this->belongsTo(Producto::class, 'producto_id');
}
```

---

## Flujo de Trabajo Típico

### Escenario 1: Registrar producción del día
1. Operador accede a `/control/produccion/create` → método `create()`
2. Llena formulario:
   - Fecha: 2025-12-02
   - Responsable: "Juan Pérez"
   - Productos:
     - Botellón 20L: 500 unidades
     - Bolo Grande: 200 unidades
   - Materiales:
     - Cloro: 5 kg
     - Etiquetas: 700 unidades
   - Gasto material: $150.00
3. Submit → método `store()`
4. Sistema:
   - Valida datos
   - Verifica que Juan Pérez no tenga otro registro hoy
   - Inicia transacción
   - Crea registro maestro (id=1)
   - Crea detalle productos:
     - produccion_id=1, producto_id=1 (Botellón), cantidad=500
     - produccion_id=1, producto_id=9 (Bolo Grande), cantidad=200
   - Crea entradas de inventario:
     - producto_id=1, tipo='entrada', cantidad=500, referencia='Producción #1'
     - producto_id=9, tipo='entrada', cantidad=200, referencia='Producción #1'
   - Crea materiales:
     - produccion_id=1, nombre='Cloro', cantidad=5
     - produccion_id=1, nombre='Etiquetas', cantidad=700
   - Commit
5. Inventario actualizado:
   - Botellones 20L: +500 unidades
   - Bolo Grande: +200 unidades

### Escenario 2: Editar producción (se cometió error)
1. Se registró 500 botellones pero fueron 600
2. Administrador va a `/control/produccion/1/edit` → método `edit()`
3. Cambia cantidad de Botellones a 600
4. Submit → método `update()`
5. Sistema:
   - Inicia transacción
   - Elimina entrada antigua del inventario (500)
   - Elimina detalle de productos antiguo
   - Crea nuevo detalle: 600 botellones
   - Crea nueva entrada inventario: +600
   - Commit
6. Resultado:
   - Se eliminó entrada de 500
   - Se agregó entrada de 600
   - Diferencia neta: +100 botellones en stock

### Escenario 3: Eliminar producción errónea
1. Se registró producción por error (día equivocado)
2. Administrador elimina → método `destroy()`
3. Sistema:
   - Inicia transacción
   - Elimina entradas de inventario (revierte +500 y +200)
   - Elimina detalles de productos
   - Elimina materiales
   - Elimina registro maestro
   - Commit
4. Inventario:
   - Botellones: -500 (revertido)
   - Bolo Grande: -200 (revertido)

---

## Consultas Útiles del Sistema

### Stock disponible de un producto:
```php
// En modelo Inventario
public static function stockDisponible($productoId)
{
    $entradas = self::where('id_producto', $productoId)
        ->where('tipo_movimiento', 'entrada')
        ->sum('cantidad');

    $salidas = self::where('id_producto', $productoId)
        ->where('tipo_movimiento', 'salida')
        ->sum('cantidad');

    return $entradas - $salidas;
}
```

### Producción total del mes:
```php
$produccionMes = ProduccionDiaria::whereMonth('fecha', now()->month)
    ->with('productos')
    ->get();

$totales = [];
foreach ($produccionMes as $prod) {
    foreach ($prod->productos as $p) {
        $nombre = $p->producto->nombre;
        $totales[$nombre] = ($totales[$nombre] ?? 0) + $p->cantidad;
    }
}
// ['Botellón 20L' => 15000, 'Bolo Grande' => 5000, ...]
```

### Material más usado:
```php
$materiales = ProduccionMaterial::selectRaw('nombre_material, SUM(cantidad) as total')
    ->groupBy('nombre_material')
    ->orderByDesc('total')
    ->get();
```

---

## Mejoras Futuras Sugeridas

### 1. Descontar insumos automáticamente:
```php
// Al crear producción, descontar del inventario de insumos
foreach ($validated['materiales'] as $mat) {
    $insumo = Insumo::where('producto_insumo', $mat['material'])->first();
    if ($insumo) {
        $insumo->decrement('stock_actual', $mat['cantidad']);
    }
}
```

### 2. Catálogo de materiales:
Tabla `materiales` en lugar de texto libre

### 3. Receta de producción:
Calcular automáticamente materiales según receta:
```php
// 1 Botellón requiere:
// - 0.01 kg Cloro
// - 1 Etiqueta
// - 1 Tapa
```

### 4. Turnos de trabajo:
```php
'turno' => 'required|in:mañana,tarde,noche',
```

### 5. Metas de producción:
```php
'meta_diaria' => 1000, // botellones
'produccion_real' => 950,
'porcentaje_cumplimiento' => 95%
```

### 6. Dashboard de producción:
- Gráfico de producción semanal/mensual
- Comparación con mes anterior
- Productos más/menos producidos
- Eficiencia por responsable

### 7. Alertas de baja producción:
```php
if ($produccionReal < $meta * 0.8) {
    // Enviar notificación
}
```

### 8. Costo por unidad:
```php
$costoUnitario = $gastoMaterial / array_sum(array_column($productos, 'cantidad'));
```

### 9. Soft delete con reversión:
En lugar de hard delete, marcar como anulado

### 10. Validación de capacidad:
```php
if ($cantidadTotal > $capacidadMaximaDiaria) {
    return back()->withErrors('Excede capacidad de planta');
}
```

---

## Seguridad Implementada

### 1. Transacciones:
- Todo o nada
- Consistencia garantizada

### 2. Validaciones:
- Tipos de datos verificados
- Cantidades mínimas (>= 1)

### 3. Prevención de duplicados:
- Un responsable por día

### 4. Auditoría:
- Registra quién movió inventario (`id_usuario`)
- Timestamp automático

### 5. Referencia única:
- 'Producción #X' permite rastrear origen

---

## Dependencias

- **Laravel 8+**: Framework
- **MySQL 5.7+**: Transacciones InnoDB
- **Eloquent ORM**: Queries y relaciones
- **Carbon**: Fechas
- **Auth**: Usuario autenticado

---

## Conclusión

Este es uno de los controladores MÁS CRÍTICOS del sistema porque:
- Registra toda la producción
- Actualiza inventario automáticamente
- Garantiza consistencia con transacciones
- Permite trazabilidad completa

Es el corazón del módulo de producción y la base del control de inventario.

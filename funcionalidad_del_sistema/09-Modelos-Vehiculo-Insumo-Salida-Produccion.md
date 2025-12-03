# Modelos Control - Documentación Línea por Línea

**Archivos documentados**:
1. `app/Models/Vehiculo.php` - Vehículos de la empresa
2. `app/Models/Control/Insumo.php` - Insumos y materias primas
3. `app/Models/Control/SalidaProducto.php` - Salidas de productos
4. `app/Models/Control/ProduccionDiaria.php` - Producción diaria

---

# 1. Modelo Vehiculo

**Ubicación**: `app/Models/Vehiculo.php`
**Tabla BD**: `vehiculos`
**Propósito**: Gestionar vehículos de la empresa (camiones, camionetas de reparto)

## Estructura Básica

### Líneas 34-42: Campos fillable
```php
protected $fillable = [
    'placa',
    'responsable',
    'modelo',
    'marca',
    'estado',
    'capacidad',
    'observacion',
];
```

**¿Qué hace cada campo?**

**placa**:
- Matrícula del vehículo (ej: "ABC-123")
- Identificador único
- **De dónde sale**: Registro vehicular
- **Para qué**: Identificar vehículo en salidas

**responsable**:
- Nombre del chofer asignado (string)
- **Nota importante**: Actualmente es texto, debería ser FK a Personal
- **Para qué**: Saber quién maneja cada vehículo

**modelo**:
- Modelo del vehículo (ej: "Ford Ranger 2020")
- **Opcional**

**marca**:
- Fabricante (ej: "Ford", "Toyota", "Nissan")

**estado**:
- Valores: 'activo', 'mantenimiento', 'inactivo'
- **activo**: Disponible para uso
- **mantenimiento**: En taller
- **inactivo**: Dado de baja

**capacidad**:
- Capacidad de carga en unidades/kg
- **Para qué**: Planificar cuántos botellones caben

**observacion**:
- Notas sobre el vehículo

---

### Líneas 47-51: Casts
```php
protected $casts = [
    'capacidad' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

**¿Qué hace?**
- **capacidad**: Convierte a entero (BD puede guardarlo como string)
- **created_at/updated_at**: Laravel timestamps automáticos

---

### Líneas 56-59: Método estaDisponible()
```php
public function estaDisponible(): bool
{
    return $this->estado === 'activo';
}
```

**¿Qué hace?**
- Verifica si vehículo está disponible para asignar a una salida

**Uso**:
```php
$vehiculo = Vehiculo::find(1);
if ($vehiculo->estaDisponible()) {
    echo "Puede usar este vehículo";
} else {
    echo "Vehículo no disponible";
}
```

---

### Líneas 64-67: Método enMantenimiento()
```php
public function enMantenimiento(): bool
{
    return $this->estado === 'mantenimiento';
}
```

**Uso**:
```php
if ($vehiculo->enMantenimiento()) {
    echo "Vehículo en taller";
}
```

---

### Líneas 74-77: Scope activos()
```php
public function scopeActivos($query)
{
    return $query->where('estado', 'activo');
}
```

**Uso**:
```php
// Solo vehículos disponibles
$disponibles = Vehiculo::activos()->get();

// Para mostrar en select de formulario de salidas
<select name="vehiculo_id">
    @foreach(Vehiculo::activos()->get() as $vehiculo)
        <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa }}</option>
    @endforeach
</select>
```

---

### Líneas 104-117: Relaciones comentadas (TODO)
```php
// public function responsablePersonal(): BelongsTo
// {
//     return $this->belongsTo(Personal::class, 'responsable_id');
// }

// public function salidas(): HasMany
// {
//     return $this->hasMany(SalidaProducto::class, 'vehiculo_id');
// }
```

**¿Por qué están comentadas?**
- **Diseño legacy**: Sistema comenzó con campo `responsable` como texto
- **TODO futuro**: Migrar a foreign key

**Cómo se usarían si estuvieran activas**:
```php
// Obtener chofer asignado:
$vehiculo = Vehiculo::find(1);
$chofer = $vehiculo->responsablePersonal;
echo $chofer->nombre_completo;

// Obtener todas las salidas que usaron este vehículo:
$salidas = $vehiculo->salidas;
foreach ($salidas as $salida) {
    echo $salida->fecha . " - " . $salida->nombre_distribuidor;
}
```

---

## Tabla BD: vehiculos

```sql
CREATE TABLE vehiculos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    placa VARCHAR(20) NOT NULL UNIQUE,
    responsable VARCHAR(255) NULL, -- TODO: migrar a responsable_id FK
    modelo VARCHAR(100) NULL,
    marca VARCHAR(50) NULL,
    estado ENUM('activo', 'mantenimiento', 'inactivo') DEFAULT 'activo',
    capacidad INT NULL,
    observacion TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_estado (estado),
    INDEX idx_placa (placa)
);
```

**Ejemplos de registros**:
```
| id | placa   | responsable | marca  | modelo        | estado | capacidad |
|----|---------|-------------|--------|---------------|--------|-----------|
| 1  | ABC-123 | Juan Pérez  | Ford   | Ranger 2020   | activo | 100       |
| 2  | XYZ-789 | Pedro López | Toyota | Hilux 2019    | activo | 80        |
| 3  | DEF-456 | NULL        | Nissan | Frontier 2015 | mant.  | 90        |
```

---

# 2. Modelo Insumo

**Ubicación**: `app/Models/Control/Insumo.php`
**Tabla BD**: `control_insumos`
**Propósito**: Registrar insumos y materias primas

## Estructura

### Líneas 13-25: Campos fillable
```php
protected $fillable = [
    'fecha',
    'producto_insumo',
    'cantidad',
    'unidad_medida',
    'numero_lote',
    'fecha_vencimiento',
    'responsable',
    'proveedor',
    'observaciones',
    'stock_actual',
    'stock_minimo',
];
```

**¿Qué hace cada campo?**

**fecha**: Fecha de ingreso del insumo

**producto_insumo**:
- Nombre del insumo (ej: "Cloro", "Etiquetas", "Tapas")
- Texto libre (no FK)

**cantidad**:
- Cantidad ingresada
- Decimal con 2 decimales

**unidad_medida**:
- "kg", "litros", "unidades", "cajas"

**numero_lote**:
- Código de lote del proveedor
- **Para qué**: Trazabilidad, recalls

**fecha_vencimiento**:
- Fecha de caducidad
- **Importante**: Para productos químicos y materiales perecederos

**responsable**:
- Quién recibió el insumo (string)

**proveedor**:
- Empresa que suministra

**observaciones**:
- Notas adicionales

**stock_actual**:
- Cantidad disponible actualmente
- Se inicializa con `cantidad` al crear
- **Se descuenta** cuando se usa en producción (manual por ahora)

**stock_minimo**:
- Nivel de alerta para reordenar

---

### Líneas 27-33: Casts
```php
protected $casts = [
    'fecha' => 'date',
    'fecha_vencimiento' => 'date',
    'cantidad' => 'decimal:2',
    'stock_actual' => 'decimal:2',
    'stock_minimo' => 'decimal:2',
];
```

**¿Qué hace?**
- **decimal:2**: Convierte a decimal con 2 decimales (ej: 10.50)
- **date**: Convierte strings a objetos Carbon

**Ejemplo**:
```php
$insumo = Insumo::find(1);
echo $insumo->cantidad; // 10.50 (decimal)
echo $insumo->fecha->format('d/m/Y'); // 02/12/2025
```

---

### Líneas 35-38: Atributos por defecto
```php
protected $attributes = [
    'stock_actual' => 0,
    'stock_minimo' => 0,
];
```

**¿Qué hace?**
- Si NO se especifica `stock_actual` al crear, se usa 0
- Si NO se especifica `stock_minimo` al crear, se usa 0

**Uso**:
```php
// Sin especificar:
Insumo::create([
    'fecha' => now(),
    'producto_insumo' => 'Cloro',
    'cantidad' => 50,
]);
// stock_actual = 0, stock_minimo = 0 (por defecto)

// Con controller que lo asigna:
$data['stock_actual'] = $data['cantidad']; // 50
Insumo::create($data);
// stock_actual = 50
```

---

### Líneas 45-48: Relación comentada
```php
// public function responsablePersonal(): BelongsTo
// {
//     return $this->belongsTo(Personal::class, 'responsable_id');
// }
```

**TODO futuro**: Migrar campo `responsable` (string) a `responsable_id` (FK)

---

## Tabla BD: control_insumos

```sql
CREATE TABLE control_insumos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    producto_insumo VARCHAR(255) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    unidad_medida VARCHAR(50) NOT NULL,
    numero_lote VARCHAR(100) NULL,
    fecha_vencimiento DATE NULL,
    responsable VARCHAR(255) NOT NULL,
    proveedor VARCHAR(255) NULL,
    observaciones TEXT NULL,
    stock_actual DECIMAL(10,2) DEFAULT 0,
    stock_minimo DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha (fecha),
    INDEX idx_producto (producto_insumo),
    INDEX idx_lote (numero_lote)
);
```

**Ejemplo de flujo**:
```
DÍA 1: Ingreso
- Llega proveedor con 100 kg de Cloro
- Se crea registro:
  cantidad: 100
  stock_actual: 100  ← Se inicializa igual
  stock_minimo: 10

DÍA 2: Uso en producción (manual por ahora)
- Se usan 5 kg de Cloro
- Se actualiza:
  stock_actual: 95  ← Se descuenta manualmente

DÍA 10: Alerta
- stock_actual (95) vs stock_minimo (10)
- Aún tiene suficiente

DÍA 50: Alerta
- stock_actual: 8
- stock_actual < stock_minimo
- ⚠️ ALERTA: Reordenar Cloro
```

---

# 3. Modelo SalidaProducto

**Ubicación**: `app/Models/Control/SalidaProducto.php`
**Tabla BD**: `control_salidas_productos`
**Propósito**: Registrar salidas de productos (el más complejo)

## Estructura

### Líneas 14-54: Campos fillable (¡50+ campos!)
```php
protected $fillable = [
    'tipo_salida',           // Tipo: Despacho/Pedido/Venta
    'nombre_distribuidor',   // Distribuidor
    'chofer',                // Chofer (string)
    'nombre_cliente',        // Cliente final
    'direccion_entrega',     // Dirección
    'telefono_cliente',      // Teléfono
    'responsable',           // Responsable
    'responsable_venta',     // Para ventas directas
    'vehiculo_placa',        // Placa del vehículo
    'fecha',                 // Fecha de salida

    // Días de semana (legacy):
    'lunes', 'martes', 'miercoles', 'jueves',
    'viernes', 'sabado', 'domingo',

    // Total de retornos:
    'retornos',

    // Retornos por tipo:
    'retorno_botellones',
    'retorno_bolo_grande',
    'retorno_bolo_pequeno',
    'retorno_gelatina',
    'retorno_agua_saborizada',
    'retorno_agua_limon',
    'retorno_agua_natural',
    'retorno_hielo',
    'retorno_dispenser',

    // Productos enviados por tipo:
    'botellones',
    'bolo_grande',
    'bolo_pequeño',
    'gelatina',
    'agua_saborizada',
    'agua_limon',
    'agua_natural',
    'hielo',
    'dispenser',
    'choreados',

    'hora_llegada',
    'observaciones',
];
```

**¿Por qué tantos campos?**

1. **Diseño legacy**: Sistema antiguo usaba columnas específicas por producto
2. **Ventaja**: Consultas y reportes más rápidos (no hay joins)
3. **Desventaja**: Agregar nuevo producto requiere migración

**Tipos de campos**:

**Campos administrativos**:
- `tipo_salida`: "Despacho Interno", "Pedido Cliente", "Venta Directa"
- `fecha`: Cuándo salió
- `hora_llegada`: Cuándo llegó a destino
- `observaciones`: Notas

**Campos de personas**:
- `nombre_distribuidor`: Quién recibe (distribuidor)
- `chofer`: Quién maneja
- `nombre_cliente`: Cliente final
- `responsable`: Quién registró
- `responsable_venta`: Para ventas directas

**Campos de productos enviados**:
- Cada tipo de producto tiene su columna
- `botellones`: Cantidad de botellones 20L enviados
- `bolo_grande`: Cantidad de bolos grandes
- etc.

**Campos de retornos**:
- Botellones son **retornables** (el distribuidor devuelve vacíos)
- `retorno_botellones`: Cuántos vacíos devolvió
- `retornos`: Total de todos los retornos

**choreados**:
- Productos perdidos/rotos en tránsito

---

### Líneas 56-59: Casts
```php
protected $casts = [
    'fecha' => 'date',
    'hora_llegada' => 'datetime:H:i',
];
```

**hora_llegada**:
- Formato HH:MM (ej: "14:30")
- NO guarda fecha completa, solo hora

---

### Líneas 74-104: Relaciones comentadas (TODOS)
```php
// public function chofer(): BelongsTo
// public function responsablePersonal(): BelongsTo
// public function responsableVenta(): BelongsTo
// public function vehiculo(): BelongsTo
```

**¿Por qué comentadas?**
- Campos actuales son strings
- Migrar a FK requiere:
  1. Crear columnas nuevas (chofer_id, vehiculo_id, etc.)
  2. Migrar datos existentes
  3. Eliminar columnas antiguas
  4. Descomentar relaciones

---

## Tabla BD: control_salidas_productos

```sql
CREATE TABLE control_salidas_productos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    tipo_salida VARCHAR(50) NOT NULL,
    nombre_distribuidor VARCHAR(255) NULL,
    chofer VARCHAR(255) NULL,
    nombre_cliente VARCHAR(255) NULL,
    direccion_entrega VARCHAR(500) NULL,
    telefono_cliente VARCHAR(20) NULL,
    vehiculo_placa VARCHAR(20) NULL,
    fecha DATE NOT NULL,

    -- Productos enviados (INT):
    botellones INT DEFAULT 0,
    bolo_grande INT DEFAULT 0,
    bolo_pequeño INT DEFAULT 0,
    gelatina INT DEFAULT 0,
    agua_saborizada INT DEFAULT 0,
    agua_limon INT DEFAULT 0,
    agua_natural INT DEFAULT 0,
    hielo INT DEFAULT 0,
    dispenser INT DEFAULT 0,
    choreados INT DEFAULT 0,

    -- Retornos (INT):
    retornos INT DEFAULT 0,
    retorno_botellones INT DEFAULT 0,
    retorno_bolo_grande INT DEFAULT 0,
    retorno_bolo_pequeno INT DEFAULT 0,
    retorno_gelatina INT DEFAULT 0,
    retorno_agua_saborizada INT DEFAULT 0,
    retorno_agua_limon INT DEFAULT 0,
    retorno_agua_natural INT DEFAULT 0,
    retorno_hielo INT DEFAULT 0,
    retorno_dispenser INT DEFAULT 0,

    -- Legacy (días de semana):
    lunes INT DEFAULT 0,
    martes INT DEFAULT 0,
    miercoles INT DEFAULT 0,
    jueves INT DEFAULT 0,
    viernes INT DEFAULT 0,
    sabado INT DEFAULT 0,
    domingo INT DEFAULT 0,

    hora_llegada TIME NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha (fecha),
    INDEX idx_tipo (tipo_salida),
    INDEX idx_distribuidor (nombre_distribuidor)
);
```

**Ejemplo de registro**:
```
Salida ID: 1
Tipo: Despacho Interno
Distribuidor: José García
Chofer: Carlos Ruiz
Vehículo: ABC-123
Fecha: 2025-12-02

Productos enviados:
- botellones: 500
- bolo_grande: 200
- agua_saborizada: 100

Retornos:
- retorno_botellones: 50 (devolvió 50 vacíos)
- retorno_bolo_grande: 10

Resultado neto:
- Salieron 500 botellones, regresaron 50 vacíos
- Inventario: -450 botellones llenos
```

---

# 4. Modelo ProduccionDiaria

**Ubicación**: `app/Models/Control/ProduccionDiaria.php`
**Tabla BD**: `control_produccion_diaria`
**Propósito**: Registro maestro de producción diaria

## Estructura

### Líneas 14-22: Campos fillable
```php
protected $fillable = [
    'fecha',
    'responsable',
    'turno',            // Legacy
    'preparacion',      // Legacy
    'rollos_material',  // Legacy
    'gasto_material',
    'observaciones',
];
```

**Campos activos**:
- **fecha**: Día de producción
- **responsable**: Quién supervisó (string)
- **gasto_material**: Costo de materiales usados
- **observaciones**: Notas

**Campos legacy** (no se usan):
- **turno**: "mañana", "tarde", "noche" (ya no se usa)
- **preparacion**: Preparación previa (ya no se usa)
- **rollos_material**: Material de empaque (ya no se usa)

---

### Líneas 31-34: Relación productos()
```php
public function productos(): HasMany
{
    return $this->hasMany(ProduccionProducto::class, 'produccion_id');
}
```

**¿Qué hace?**
- Una producción tiene MUCHOS productos
- Tabla intermedia: `control_produccion_productos`

**Uso**:
```php
$produccion = ProduccionDiaria::find(1);
$productos = $produccion->productos;

foreach ($productos as $prod) {
    echo $prod->producto->nombre . ": " . $prod->cantidad;
}
// Botellón 20L: 500
// Bolo Grande: 200
```

---

### Líneas 39-42: Relación materiales()
```php
public function materiales(): HasMany
{
    return $this->hasMany(ProduccionMaterial::class, 'produccion_id');
}
```

**¿Qué hace?**
- Una producción usa MUCHOS materiales
- Tabla intermedia: `control_produccion_materiales`

**Uso**:
```php
$produccion = ProduccionDiaria::find(1);
$materiales = $produccion->materiales;

foreach ($materiales as $mat) {
    echo $mat->nombre_material . ": " . $mat->cantidad;
}
// Cloro: 5 kg
// Etiquetas: 500 unidades
```

---

## Tabla BD: control_produccion_diaria

```sql
CREATE TABLE control_produccion_diaria (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    turno VARCHAR(50) NULL,
    preparacion VARCHAR(255) NULL,
    rollos_material INT DEFAULT 0,
    gasto_material DECIMAL(10,2) DEFAULT 0,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha (fecha),
    INDEX idx_responsable (responsable)
);
```

---

## Tablas Relacionadas

### control_produccion_productos
```sql
CREATE TABLE control_produccion_productos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    produccion_diaria_id BIGINT UNSIGNED NOT NULL,
    producto_id BIGINT UNSIGNED NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (produccion_diaria_id) REFERENCES control_produccion_diaria(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
```

### control_produccion_materiales
```sql
CREATE TABLE control_produccion_materiales (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    produccion_diaria_id BIGINT UNSIGNED NOT NULL,
    nombre_material VARCHAR(255) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (produccion_diaria_id) REFERENCES control_produccion_diaria(id) ON DELETE CASCADE
);
```

---

## Flujo Completo de Producción

```
PASO 1: Crear Producción
ProduccionDiaria::create([
    'fecha' => '2025-12-02',
    'responsable' => 'Juan Pérez',
    'gasto_material' => 150.00,
]);
// ID: 1

PASO 2: Agregar Productos
$produccion->productos()->create([
    'producto_id' => 1,  // Botellón 20L
    'cantidad' => 500,
]);

PASO 3: Agregar Materiales
$produccion->materiales()->create([
    'nombre_material' => 'Cloro',
    'cantidad' => 5,
]);

PASO 4: Crear Movimientos de Inventario (automático en controller)
Inventario::create([
    'id_producto' => 1,
    'tipo_movimiento' => 'entrada',
    'cantidad' => 500,
    'referencia' => 'Producción #1',
]);
```

---

## Resumen Comparativo de Modelos

| Modelo | Tabla | Propósito | Relaciones |
|--------|-------|-----------|------------|
| Vehiculo | vehiculos | Vehículos de reparto | Ninguna (TODOs) |
| Insumo | control_insumos | Materias primas | Ninguna (TODOs) |
| SalidaProducto | control_salidas_productos | Despachos | Ninguna (TODOs) |
| ProduccionDiaria | control_produccion_diaria | Producción | productos(), materiales() |

---

## Mejoras Pendientes (TODOs)

### 1. Migrar strings a FK:
```php
// Actual:
'responsable' => 'Juan Pérez'

// Propuesto:
'responsable_id' => 1  // FK a personal.id
```

### 2. Activar relaciones Eloquent:
```php
// Poder usar:
$vehiculo->responsablePersonal->nombre_completo
$vehiculo->salidas
$insumo->responsablePersonal
$salida->chofer->nombre_completo
$salida->vehiculo->placa
```

### 3. Normalizar SalidaProducto:
En lugar de 50+ columnas, tabla intermedia:
```sql
CREATE TABLE control_salidas_detalle (
    id BIGINT PRIMARY KEY,
    salida_id BIGINT FK,
    producto_id BIGINT FK,
    cantidad INT,
    es_retorno BOOLEAN
);
```

**Ventajas**:
- Agregar productos sin migración
- Menos columnas
- Más flexible

**Desventajas**:
- Requiere joins (más lento)
- Migración compleja del sistema actual

---

**Continuará**: Próximos archivos a documentar:
- Middleware
- Commands
- Más modelos
- Requests
- Controllers de Admin/Produccion

¿Quieres que continúe ahora?

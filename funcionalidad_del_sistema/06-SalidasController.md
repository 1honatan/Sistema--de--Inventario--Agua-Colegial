# SalidasController - Documentación Detallada (PARTE 1)

**Ubicación**: `app/Http/Controllers/Control/SalidasController.php`

## Propósito General
Este es el controlador MÁS COMPLEJO del sistema. Gestiona las salidas de productos terminados del almacén hacia distribuidores, clientes o ventas directas. Maneja TRES tipos de salidas diferentes, registra retornos (botellones vacíos), valida stock disponible, y actualiza el inventario automáticamente. También integra información de vehículos, choferes y distribuidores.

**Tipos de salidas**:
1. **Despacho Interno**: Salida a distribuidores con vehículo de la empresa
2. **Pedido Cliente**: Entrega directa a cliente específico
3. **Venta Directa**: Venta en el local sin transporte

---

## Línea por Línea

### Líneas 1-14: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\SalidaProducto;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
```

**¿Qué hace?**
- **Línea 6**: Modelo SalidaProducto (registro principal de salida)
- **Línea 7**: Personal (choferes, distribuidores, responsables)
- **Línea 8**: Producto (catálogo)
- **Línea 9**: Inventario (movimientos de stock) **CRÍTICO**
- **Línea 10**: Vehiculo (placas y datos de vehículos)
- **Línea 12**: Auth para usuario autenticado
- **Línea 13**: DB para transacciones

**¿De dónde sale?**
- Laravel framework y modelos del sistema

---

### Líneas 20-41: Método `index()` - Listar salidas por semana
```php
public function index(Request $request)
{
    // Líneas 22-23: Obtener semana solicitada
    $semana = (int) $request->get('semana', 0);

    $inicioSemana = now()->addWeeks($semana)->startOfWeek();
    $finSemana = now()->addWeeks($semana)->endOfWeek();

    // Líneas 28-34: Construir consulta con filtro opcional
    $query = SalidaProducto::whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);

    if ($request->filled('tipo_salida')) {
        $query->where('tipo_salida', $request->tipo_salida);
    }

    $salidas = $query->orderBy('fecha', 'desc')
        ->orderBy('id', 'desc')
        ->get();

    return view('control.salidas.index', compact('salidas', 'inicioSemana', 'finSemana', 'semana'));
}
```

**¿Qué hace?**
- Similar a ProduccionDiariaController pero con FILTRO adicional
- **Líneas 32-34**: Si hay parámetro `tipo_salida`, filtra por tipo
  - Ejemplo: `?tipo_salida=Despacho Interno`
- **Líneas 36-37**: Ordena por fecha desc, luego por ID desc (más recientes primero)

**¿De dónde sale?**
- URL: `?semana=0&tipo_salida=Pedido Cliente`

**¿Para qué sirve?**
- Vista semanal de salidas
- Filtrar por tipo específico
- Navegación entre semanas

---

### Líneas 46-92: Método `create()` - Formulario de creación (COMPLEJO)
```php
public function create()
{
    // Líneas 48-52: Obtener Choferes activos
    $choferes = Personal::where('estado', 'activo')
        ->where('cargo', 'Chofer')
        ->orderBy('nombre_completo')
        ->get();

    // Líneas 54-58: Obtener Distribuidores activos
    $distribuidores = Personal::where('estado', 'activo')
        ->where('cargo', 'Distribuidor')
        ->orderBy('nombre_completo')
        ->get();

    // Líneas 60-64: Obtener responsables para venta directa
    $responsablesVenta = Personal::where('estado', 'activo')
        ->whereNotIn('cargo', ['Chofer', 'Distribuidor'])
        ->orderBy('nombre_completo')
        ->get();
```

**¿Qué hace?**
- **Líneas 49-52**: Lista solo empleados con cargo='Chofer'
- **Líneas 55-58**: Lista solo empleados con cargo='Distribuidor'
- **Líneas 61-64**: Lista empleados SIN cargo de Chofer ni Distribuidor
  - Para ventas directas (ej: Vendedor, Cajero, Encargado)

**¿De dónde sale?**
- Campo `cargo` en tabla `personal`

**¿Para qué sirve?**
- Separar roles para diferentes tipos de salidas
- Despacho Interno: requiere chofer + distribuidor
- Pedido Cliente: puede incluir chofer/distribuidor
- Venta Directa: requiere responsable de venta

```php
    // Líneas 66-69: Obtener vehículos activos
    $vehiculos = Vehiculo::where('estado', 'activo')
        ->orderBy('placa')
        ->get();

    // Líneas 71-74: Obtener todo el personal (para mapeo)
    $personal = Personal::where('estado', 'activo')
        ->orderBy('nombre_completo')
        ->get();
```

**¿Qué hace?**
- **Líneas 67-69**: Lista vehículos disponibles ordenados por placa
- **Líneas 72-74**: Lista completa de personal activo

**¿Para qué sirve?**
- Seleccionar vehículo para el despacho
- Mapear relación vehículo-responsable (cada vehículo puede tener chofer asignado)

```php
    // Líneas 76-89: Obtener productos con stock dinámico
    $productos = Producto::where('estado', 'activo')
        ->orderBy('nombre')
        ->get()
        ->map(function ($producto) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'unidad_medida' => $producto->unidad_medida,
                'unidades_por_paquete' => $producto->unidades_por_paquete,
                'stock' => Inventario::stockDisponible($producto->id),
                'icono' => self::obtenerIconoProducto($producto->nombre),
            ];
        });

    return view('control.salidas.create', compact('choferes', 'distribuidores', 'responsablesVenta', 'vehiculos', 'personal', 'productos'));
}
```

**¿Qué hace? (IMPORTANTE)**
- **Líneas 77-79**: Obtiene productos activos
- **Línea 80**: `.map()`: Transforma cada producto
- **Líneas 81-88**: Para cada producto, crea objeto con:
  - **id**: ID del producto
  - **nombre**: Nombre
  - **unidad_medida**: "unidades", "kg", "litros"
  - **unidades_por_paquete**: Cuántas unidades trae un paquete
  - **stock**: **CALCULA DINÁMICAMENTE** stock disponible
    - Llama a `Inventario::stockDisponible($id)`
    - Suma entradas - suma salidas
  - **icono**: Icono Font Awesome para UI

**¿De dónde sale?**
- Tabla `productos`
- Método estático en modelo Inventario
- Método privado `obtenerIconoProducto()` de esta clase

**¿Para qué sirve?**
- Mostrar stock en tiempo real en el formulario
- Prevenir salidas mayores al stock disponible
- UI más amigable con iconos

---

### Líneas 97-111: Método privado `obtenerIconoProducto()` - Iconos para UI
```php
private static function obtenerIconoProducto($nombre)
{
    $nombre = strtolower($nombre);

    if (str_contains($nombre, 'botell')) return 'fa-water';
    if (str_contains($nombre, 'bolo')) return 'fa-shopping-bag';
    if (str_contains($nombre, 'gelatina')) return 'fa-cube';
    if (str_contains($nombre, 'agua') && str_contains($nombre, 'sabor')) return 'fa-tint';
    if (str_contains($nombre, 'agua') && str_contains($nombre, 'natural')) return 'fa-water';
    if (str_contains($nombre, 'agua') && str_contains($nombre, 'lim')) return 'fa-lemon';
    if (str_contains($nombre, 'hielo')) return 'fa-snowflake';
    if (str_contains($nombre, 'dispenser')) return 'fa-faucet';

    return 'fa-box'; // Icono por defecto
}
```

**¿Qué hace?**
- Método privado estático
- Recibe nombre de producto
- Retorna clase de icono Font Awesome

**Lógica**:
- Convierte a minúsculas
- Busca palabras clave con `str_contains()`
- Asigna iconos temáticos

**Iconos**:
- Botellones: 🌊 `fa-water`
- Bolos: 🛍️ `fa-shopping-bag`
- Gelatina: 📦 `fa-cube`
- Agua saborizada: 💧 `fa-tint`
- Agua limón: 🍋 `fa-lemon`
- Hielo: ❄️ `fa-snowflake`
- Dispenser: 🚰 `fa-faucet`
- Otros: 📦 `fa-box`

**¿Para qué sirve?**
- Interfaz visual más clara
- Identificar productos rápidamente
- Experiencia de usuario mejorada

---

## (CONTINUARÁ EN PARTE 2)

Este controlador es extremadamente complejo con 648 líneas. Por claridad, dividiré la documentación en partes:

- **PARTE 1** (esta): Líneas 1-111 - Index, Create, Helpers
- **PARTE 2**: Líneas 116-355 - Método store() (guardar salida)
- **PARTE 3**: Líneas 360-607 - Métodos show(), edit(), update()
- **PARTE 4**: Líneas 612-647 - Método destroy() y generarPDF()
- **PARTE 5**: Resumen, tablas, flujos de trabajo, mejoras

---

# SalidasController - Documentación Detallada (PARTE 2 - store)

## Líneas 116-355: Método `store()` - Guardar salida (MUY COMPLEJO)

Este es el método más complejo de todo el sistema. Maneja 3 tipos de salidas, valida stock, registra productos enviados, retornos, actualiza inventario, y todo dentro de transacciones.

```php
public function store(Request $request)
{
    // Líneas 118-160: Validación DINÁMICA según tipo de salida
    $rules = [
        'tipo_salida' => 'required|string|max:50',
        'fecha' => 'nullable|date',
    ];

    // Líneas 125-130: Validaciones para Despacho Interno
    if ($request->tipo_salida === 'Despacho Interno') {
        $rules['chofer'] = 'required|string|max:255';
        $rules['nombre_distribuidor'] = 'required|string|max:255';
        $rules['vehiculo_placa'] = 'nullable|string|max:255';
        $rules['hora_llegada'] = 'nullable|date_format:H:i';
        $rules['fecha'] = 'required|date';
```

**¿Qué hace?**
- Validación condicional según `tipo_salida`
- **Despacho Interno requiere**:
  - chofer (obligatorio)
  - nombre_distribuidor (obligatorio)
  - vehiculo_placa (opcional)
  - hora_llegada (opcional, formato HH:MM)
  - fecha (obligatoria para este tipo)

```php
    // Líneas 131-139: Validaciones para Pedido Cliente
    } elseif ($request->tipo_salida === 'Pedido Cliente') {
        $rules['nombre_cliente'] = 'required|string|max:255';
        $rules['direccion_entrega'] = 'required|string|max:500';
        $rules['telefono_cliente'] = 'nullable|string|max:20';
        $rules['chofer'] = 'nullable|string|max:255';
        $rules['nombre_distribuidor'] = 'nullable|string|max:255';
        $rules['vehiculo_placa'] = 'nullable|string|max:255';
        $rules['hora_llegada'] = 'nullable|date_format:H:i';
        $rules['fecha'] = 'required|date';
```

**¿Qué hace?**
- **Pedido Cliente requiere**:
  - nombre_cliente (obligatorio)
  - direccion_entrega (obligatoria, máx 500 caracteres)
  - telefono_cliente (opcional)
  - chofer (opcional - puede recoger el cliente)
  - distribuidor (opcional)
  - vehiculo, hora_llegada (opcionales)

```php
    // Líneas 140-144: Validaciones para Venta Directa
    } elseif ($request->tipo_salida === 'Venta Directa') {
        $rules['nombre_cliente'] = 'required|string|max:255';
        $rules['responsable_venta'] = 'required|string|max:255';
        $rules['fecha'] = 'nullable|date';
    }
```

**¿Qué hace?**
- **Venta Directa requiere**:
  - nombre_cliente (obligatorio)
  - responsable_venta (obligatorio - quién atendió)
  - fecha (opcional - puede ser hoy)

```php
    // Líneas 146-160: Validaciones comunes de productos
    $rules = array_merge($rules, [
        'lunes' => 'nullable|integer|min:0',
        'martes' => 'nullable|integer|min:0',
        'miercoles' => 'nullable|integer|min:0',
        'jueves' => 'nullable|integer|min:0',
        'viernes' => 'nullable|integer|min:0',
        'sabado' => 'nullable|integer|min:0',
        'domingo' => 'nullable|integer|min:0',
        'productos' => 'nullable|array',
        'productos.*' => 'nullable|integer|min:0',
        'retornos' => 'nullable|array',
        'retornos.*' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string',
    ]);

    $validated = $request->validate($rules);
```

**¿Qué hace?**
- Merge de validaciones base + específicas
- **Días de la semana**: Campos legacy (sistema antiguo)
  - Opcional, números enteros >= 0
- **productos**: Array con cantidades por producto_id
  - Ejemplo: `[1 => 500, 3 => 200]` (producto 1: 500 unidades, producto 3: 200)
- **retornos**: Array con cantidades de retornos por producto_id
  - Ejemplo: `[1 => 50]` (50 botellones vacíos devueltos)

**Estructura de datos**:
```php
[
    'tipo_salida' => 'Despacho Interno',
    'fecha' => '2025-12-02',
    'chofer' => 'Carlos Ruiz',
    'nombre_distribuidor' => 'José García',
    'vehiculo_placa' => 'ABC-123',
    'hora_llegada' => '14:30',
    'productos' => [
        1 => 500,  // 500 Botellones 20L
        9 => 200,  // 200 Bolos Grandes
    ],
    'retornos' => [
        1 => 50,   // 50 botellones vacíos devueltos
    ],
    'observaciones' => 'Entrega normal'
]
```

```php
    // Líneas 164-175: Validación de duplicados
    if ($request->filled('nombre_distribuidor') && $request->filled('fecha')) {
        $existeDuplicado = SalidaProducto::where('nombre_distribuidor', $request->nombre_distribuidor)
            ->whereDate('fecha', $request->fecha)
            ->exists();

        if ($existeDuplicado) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Ya existe una salida registrada para ' . $request->nombre_distribuidor . ' en la fecha ' . date('d/m/Y', strtotime($request->fecha)) . '. Por favor, verifique los registros existentes o edite el registro anterior.']);
        }
    }
```

**¿Qué hace?**
- Si hay distribuidor Y fecha, valida duplicados
- Evita múltiples salidas del mismo distribuidor en un día
- Sugiere editar el registro existente

**¿Por qué?**
- Un distribuidor sale UNA VEZ por día
- Si necesita más productos, se edita la salida existente

```php
    // Líneas 177-185: Normalizar nombre_distribuidor
    if ($request->tipo_salida === 'Venta Directa') {
        $validated['nombre_distribuidor'] = $validated['nombre_cliente'] ?? '';
    } elseif ($request->tipo_salida === 'Pedido Cliente') {
        if (empty($validated['nombre_distribuidor'])) {
            $validated['nombre_distribuidor'] = $validated['nombre_cliente'] ?? '';
        }
    }
```

**¿Qué hace?**
- **Venta Directa**: Usa nombre del cliente como "distribuidor"
- **Pedido Cliente**: Si no hay distribuidor, usa nombre del cliente
- **Despacho Interno**: Ya tiene nombre_distribuidor

**¿Por qué?**
- Campo `nombre_distribuidor` es obligatorio en BD
- Permite reportes unificados

```php
    // Líneas 187-211: Validar stock disponible ANTES de guardar (CRÍTICO)
    $productosEnviados = $validated['productos'] ?? [];

    $erroresStock = [];
    foreach ($productosEnviados as $productoId => $cantidad) {
        if ($cantidad > 0) {
            $producto = Producto::find($productoId);

            if ($producto) {
                $stockDisponible = Inventario::stockDisponible($producto->id);

                if ($stockDisponible < $cantidad) {
                    $erroresStock[] = "No hay suficiente stock de {$producto->nombre}. Disponible: {$stockDisponible}, Solicitado: {$cantidad}";
                }
            }
        }
    }

    if (!empty($erroresStock)) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error de stock: ' . implode('. ', $erroresStock));
    }
```

**¿Qué hace? (MUY IMPORTANTE)**
- **ANTES** de crear la salida, verifica stock
- Para cada producto con cantidad > 0:
  1. Busca el producto
  2. Calcula stock disponible (entradas - salidas)
  3. Compara stock vs cantidad solicitada
  4. Si no hay suficiente, agrega mensaje a array de errores
- Si hay errores, retorna SIN guardar nada

**¿Por qué es crítico?**
- Previene sobreventa
- Evita stock negativo
- Valida ANTES de transacción

**Ejemplo de error**:
```
Error de stock: No hay suficiente stock de Botellón 20L. Disponible: 300, Solicitado: 500. No hay suficiente stock de Bolo Grande. Disponible: 50, Solicitado: 200.
```

```php
    DB::beginTransaction();

    try {
        // Líneas 216-217: Preparar datos base (sin arrays)
        $datosBasicos = array_diff_key($validated, ['productos' => '', 'retornos' => '']);
```

**¿Qué hace?**
- Inicia transacción
- Separa datos base de arrays productos/retornos
- `array_diff_key()`: Elimina claves específicas del array

```php
        // Líneas 219-263: Mapear retornos a campos específicos
        $retornosRecibidos = $validated['retornos'] ?? [];
        $productosMap = [
            1 => 'retorno_botellones',      // Botellón 20L
            3 => 'retorno_agua_natural',    // Agua Natural
            4 => 'retorno_agua_saborizada', // Agua Saborizada
            6 => 'retorno_gelatina',        // Gelatina
            8 => 'retorno_hielo',           // Hielo en Bolsa 3kg
            9 => 'retorno_bolo_grande',     // Bolo Grande
            10 => 'retorno_bolo_pequeno',   // Bolo Pequeño
            11 => 'retorno_dispenser',      // Dispenser
            12 => 'retorno_agua_limon',     // Agua De Limon
        ];

        // Inicializar todos los campos de retorno en 0
        $datosBasicos['retorno_botellones'] = 0;
        $datosBasicos['retorno_bolo_grande'] = 0;
        $datosBasicos['retorno_bolo_pequeno'] = 0;
        $datosBasicos['retorno_gelatina'] = 0;
        $datosBasicos['retorno_agua_saborizada'] = 0;
        $datosBasicos['retorno_agua_limon'] = 0;
        $datosBasicos['retorno_agua_natural'] = 0;
        $datosBasicos['retorno_hielo'] = 0;
        $datosBasicos['retorno_dispenser'] = 0;

        // Sumar retornos por tipo de producto
        foreach ($retornosRecibidos as $productoId => $cantidad) {
            if ($cantidad > 0 && isset($productosMap[$productoId])) {
                $campo = $productosMap[$productoId];
                $datosBasicos[$campo] += $cantidad;
            }
        }

        // Calcular total de retornos
        $datosBasicos['retornos'] = array_sum([
            $datosBasicos['retorno_botellones'],
            $datosBasicos['retorno_bolo_grande'],
            $datosBasicos['retorno_bolo_pequeno'],
            $datosBasicos['retorno_gelatina'],
            $datosBasicos['retorno_agua_saborizada'],
            $datosBasicos['retorno_agua_limon'],
            $datosBasicos['retorno_agua_natural'],
            $datosBasicos['retorno_hielo'],
            $datosBasicos['retorno_dispenser'],
        ]);
```

**¿Qué hace? (COMPLEJO)**
- La tabla `control_salidas_productos` tiene columnas específicas para cada tipo de retorno
- **Mapeo**: Convierte ID de producto a nombre de columna
  - Producto 1 (Botellón) → columna `retorno_botellones`
  - Producto 9 (Bolo Grande) → columna `retorno_bolo_grande`
- **Inicialización**: Pone todos los retornos en 0
- **Suma**: Recorre retornos y suma a la columna correspondiente
- **Total**: Suma todos los retornos

**¿Por qué este diseño?**
- Legacy: Sistema antiguo usaba columnas individuales
- Permite reportes rápidos por tipo
- Compatible con sistema de cuadros semanales

**Ejemplo**:
```php
// Entrada:
'retornos' => [1 => 50, 9 => 20]

// Resultado:
'retorno_botellones' => 50,
'retorno_bolo_grande' => 20,
'retorno_bolo_pequeno' => 0,
...
'retornos' => 70  // Total
```

```php
        // Líneas 265-296: Mapear productos enviados a campos específicos
        $productosEnviadosMap = [
            1 => 'botellones',           // Botellón 20L
            3 => 'agua_natural',         // Agua Natural
            4 => 'agua_saborizada',      // Agua Saborizada
            6 => 'gelatina',             // Gelatina
            8 => 'hielo',                // Hielo en Bolsa 3kg
            9 => 'bolo_grande',          // Bolo Grande
            10 => 'bolo_pequeño',        // Bolo Pequeño
            11 => 'dispenser',           // Dispenser
            12 => 'agua_limon',          // Agua De Limon
        ];

        // Inicializar campos de productos enviados
        $datosBasicos['botellones'] = 0;
        $datosBasicos['bolo_grande'] = 0;
        $datosBasicos['bolo_pequeño'] = 0;
        $datosBasicos['gelatina'] = 0;
        $datosBasicos['agua_saborizada'] = 0;
        $datosBasicos['agua_limon'] = 0;
        $datosBasicos['agua_natural'] = 0;
        $datosBasicos['hielo'] = 0;
        $datosBasicos['dispenser'] = 0;
        $datosBasicos['choreados'] = 0;

        // Sumar productos enviados por tipo
        foreach ($productosEnviados as $productoId => $cantidad) {
            if ($cantidad > 0 && isset($productosEnviadosMap[$productoId])) {
                $campo = $productosEnviadosMap[$productoId];
                $datosBasicos[$campo] += $cantidad;
            }
        }

        // Línea 299: Crear el registro de salida
        $salida = SalidaProducto::create($datosBasicos);
```

**¿Qué hace?**
- Mismo proceso que retornos, pero para productos ENVIADOS
- Mapea ID de producto a nombre de columna
- Inicializa todos en 0
- Suma cantidades
- **Línea 299**: Crea registro en tabla `control_salidas_productos`

**¿Por qué choreados?**
- Campo para registrar productos que se perdieron/dañaron en tránsito
- Inicializado en 0 por defecto

```php
        // Líneas 301-320: Registrar cada producto en inventario como SALIDA
        foreach ($productosEnviados as $productoId => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($productoId);

                if ($producto) {
                    Inventario::create([
                        'id_producto' => $producto->id,
                        'tipo_movimiento' => 'salida',
                        'cantidad' => $cantidad,
                        'origen' => 'Almacén',
                        'destino' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                        'referencia' => 'Salida #' . $salida->id,
                        'id_usuario' => Auth::id(),
                        'fecha_movimiento' => $validated['fecha'],
                        'observacion' => 'Salida automática desde Control de Salidas - Distribuidor: ' . $validated['nombre_distribuidor'],
                    ]);
                }
            }
        }
```

**¿Qué hace? (CRÍTICO para inventario)**
- Para cada producto enviado > 0:
  1. Busca el producto
  2. Crea movimiento de inventario tipo 'salida'
  3. **DISMINUYE** el stock automáticamente
- Campos importantes:
  - **tipo_movimiento**: 'salida' (reduce stock)
  - **origen**: 'Almacén'
  - **destino**: 'Distribuidor: Nombre'
  - **referencia**: 'Salida #X' (para rastrear)
  - **id_usuario**: Quién registró
  - **fecha_movimiento**: Fecha de salida (no de registro)

**¿Por qué es crítico?**
- Sincroniza inventario automáticamente
- Cada salida reduce el stock
- Rastreable por referencia única

```php
        // Líneas 322-341: Registrar RETORNOS como ENTRADA de inventario
        foreach ($retornosRecibidos as $productoId => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($productoId);

                if ($producto) {
                    Inventario::create([
                        'id_producto' => $producto->id,
                        'tipo_movimiento' => 'entrada',
                        'cantidad' => $cantidad,
                        'origen' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                        'destino' => 'Almacén',
                        'referencia' => 'Retorno - Salida #' . $salida->id,
                        'id_usuario' => Auth::id(),
                        'fecha_movimiento' => $validated['fecha'],
                        'observacion' => 'Retorno automático desde Control de Salidas - Distribuidor: ' . $validated['nombre_distribuidor'],
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('control.salidas.index')
            ->with('success', 'Registro de salida creado exitosamente. Inventario actualizado con ' . count(array_filter($productosEnviados)) . ' salidas y ' . count(array_filter($retornosRecibidos)) . ' retornos.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al guardar la salida: ' . $e->getMessage());
    }
}
```

**¿Qué hace? (MUY IMPORTANTE)**
- Registra retornos como **ENTRADAS** de inventario
- Los botellones vacíos devueltos AUMENTAN el stock
- Referencia: 'Retorno - Salida #X'
- **origen**: Distribuidor
- **destino**: Almacén
- **tipo_movimiento**: 'entrada' (aumenta stock)

**¿Por qué?**
- Los botellones son retornables
- Cuando el distribuidor devuelve vacíos, vuelven al inventario
- Se pueden rellenar y vender nuevamente

**Flujo completo**:
1. Validar datos
2. Validar duplicados
3. Validar stock disponible
4. Iniciar transacción
5. Mapear productos y retornos a columnas
6. Crear registro de salida
7. Registrar salidas en inventario (reduce stock)
8. Registrar retornos en inventario (aumenta stock)
9. Commit
10. Redirigir con mensaje

**Si falla algo**:
- Rollback
- No se guarda nada
- Stock queda intacto

---

# (CONTINUARÁ EN PARTE 3 - Update/Edit/Show/Destroy)

La documentación continúa con los métodos de edición, actualización, visualización y eliminación de salidas, seguido por resumen completo, tablas de BD, flujos de trabajo y mejoras sugeridas.

¿Quieres que continúe con la Parte 3 ahora o prefieres que haga un resumen de lo documentado hasta el momento?

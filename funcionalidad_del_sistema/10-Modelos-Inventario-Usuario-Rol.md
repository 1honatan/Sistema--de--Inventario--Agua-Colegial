# 10. MODELOS: INVENTARIO, USUARIO Y ROL

## 📋 ÍNDICE DE CONTENIDO

1. [Inventario.php - Modelo Crítico de Movimientos](#inventariophp)
2. [Usuario.php - Autenticación y Gestión de Usuarios](#usuariophp)
3. [Rol.php - Sistema de Roles](#rolphp)
4. [Resumen de Funcionalidades](#resumen)
5. [Tablas de Base de Datos](#tablas)
6. [Flujos de Trabajo](#flujos)
7. [Comunicación entre Modelos](#comunicacion)
8. [TODOs y Mejoras Futuras](#todos)

---

## 🎯 PROPÓSITO GENERAL

Este documento explica **línea por línea** tres modelos fundamentales del sistema:

1. **Inventario.php**: El corazón del sistema - registra TODOS los movimientos de stock
2. **Usuario.php**: Autenticación, autorización y gestión de usuarios del sistema
3. **Rol.php**: Define permisos y niveles de acceso

**¿Por qué son críticos?**
- **Inventario**: Alimentado automáticamente por ProduccionDiariaController y SalidasController
- **Usuario/Rol**: Base del sistema de seguridad y permisos

---

# INVENTARIO.PHP

**Ubicación**: `app/Models/Inventario.php`
**Líneas totales**: 198
**Complejidad**: Alta
**Propósito**: Registrar y consultar movimientos de inventario en tiempo real

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DECLARACIONES Y NAMESPACE (Líneas 1-9)

```php
<?php
```
**¿Qué hace?** Etiqueta de apertura PHP obligatoria.
**¿De dónde sale?** Estándar PHP.
**¿Para qué sirve?** Indica al servidor que el archivo contiene código PHP.

---

```php
declare(strict_types=1);
```
**¿Qué hace?** Activa el modo estricto de tipos en PHP.
**¿De dónde sale?** Característica de PHP 7+.
**¿Para qué sirve?**
- Evita conversiones automáticas de tipos (ej: `"5"` no se convierte a `5`)
- Previene errores sutiles en operaciones matemáticas
- Ejemplo: Si una función espera `int $cantidad`, y se pasa `"10"`, PHP lanzará error en lugar de convertir

---

```php
namespace App\Models;
```
**¿Qué hace?** Define el espacio de nombres del modelo.
**¿De dónde sale?** Convención PSR-4 de Laravel.
**¿Para qué sirve?** Organizar clases y evitar conflictos de nombres.

---

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
```
**¿Qué hace?** Importa clases necesarias.
**¿De dónde sale?** Framework Laravel.
**¿Para qué sirve?**
- `Model`: Clase base para todos los modelos Eloquent
- `BelongsTo`: Define relaciones de pertenencia (un inventario pertenece a un producto)

---

### 🟢 SECCIÓN 2: DOCUMENTACIÓN PHPDOC (Líneas 10-27)

```php
/**
 * Modelo de movimiento de inventario.
 *
 * @property int $id
 * @property int $id_producto
 * @property string $tipo_movimiento (entrada|salida)
 * @property int $cantidad
 * @property string|null $origen
 * @property string|null $destino
 * @property string|null $referencia
 * @property int|null $id_usuario
 * @property \Carbon\Carbon $fecha_movimiento
 * @property string|null $observacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Producto $producto
 * @property-read Usuario|null $usuario
 */
```
**¿Qué hace?** Documenta todas las propiedades del modelo.
**¿De dónde sale?** Refleja la estructura de la tabla `inventario` en BD.
**¿Para qué sirve?**
- Los IDEs (editores) ofrecen autocompletado
- Documentación para desarrolladores
- Previene errores al escribir código

**Propiedades clave**:
- `tipo_movimiento`: Solo acepta `'entrada'` o `'salida'`
- `referencia`: Link al registro que originó el movimiento (ej: `"produccion-123"`, `"salida-456"`)
- `origen/destino`: Descripciones textuales (ej: "Producción diaria", "Despacho Interno")

---

### 🟢 SECCIÓN 3: CLASE Y CONFIGURACIÓN (Líneas 28-58)

```php
class Inventario extends Model
{
```
**¿Qué hace?** Define la clase Inventario heredando de Model.
**¿De dónde sale?** Eloquent ORM de Laravel.
**¿Para qué sirve?** Proporciona métodos como `save()`, `find()`, `where()`, etc.

---

```php
    protected $table = 'inventario';
```
**¿Qué hace?** Especifica el nombre de la tabla en la BD.
**¿De dónde sale?** Configuración manual del desarrollador.
**¿Para qué sirve?** Laravel por defecto buscaría tabla `inventarios` (plural inglés), esto lo corrige.

---

```php
    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'origen',
        'destino',
        'referencia',
        'id_usuario',
        'fecha_movimiento',
        'observacion',
    ];
```
**¿Qué hace?** Define qué campos pueden asignarse masivamente.
**¿De dónde sale?** Protección de Laravel contra Mass Assignment.
**¿Para qué sirve?**
- Permite `Inventario::create($data)` solo con estos campos
- Previene que un atacante modifique `id` o `created_at`
- **Ejemplo**:
  ```php
  // ✅ Esto funciona:
  Inventario::create([
      'id_producto' => 1,
      'tipo_movimiento' => 'entrada',
      'cantidad' => 100
  ]);

  // ❌ Esto NO funciona (protegido):
  Inventario::create([
      'id' => 999, // Ignorado por seguridad
      'created_at' => '2020-01-01' // Ignorado
  ]);
  ```

---

```php
    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
```
**¿Qué hace?** Convierte automáticamente tipos de datos.
**¿De dónde sale?** Característica de Eloquent.
**¿Para qué sirve?**
- `'datetime'`: Convierte string de BD a objeto Carbon (para manipular fechas)
- `'integer'`: Asegura que cantidad sea número entero
- **Ejemplo**:
  ```php
  $movimiento = Inventario::find(1);

  // Sin casts: "2025-12-01 10:30:00" (string)
  // Con casts: Carbon instance
  echo $movimiento->fecha_movimiento->format('d/m/Y'); // "01/12/2025"
  echo $movimiento->fecha_movimiento->diffForHumans(); // "hace 2 horas"
  ```

---

### 🟢 SECCIÓN 4: RELACIONES ELOQUENT (Líneas 60-74)

```php
    /**
     * Relación: Un movimiento de inventario pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
```
**¿Qué hace?** Define relación de pertenencia con modelo Producto.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Obtener información del producto desde el movimiento.

**Flujo de datos**:
```
inventario.id_producto (FK) → productos.id (PK)
```

**Ejemplo de uso**:
```php
$movimiento = Inventario::find(1);

// ❌ Sin relación (2 consultas):
$producto = Producto::find($movimiento->id_producto);
echo $producto->nombre_producto;

// ✅ Con relación (1 consulta con join):
echo $movimiento->producto->nombre_producto;

// ✅ Eager loading (evita N+1):
$movimientos = Inventario::with('producto')->get();
foreach ($movimientos as $mov) {
    echo $mov->producto->nombre_producto; // No consulta adicional
}
```

---

```php
    /**
     * Relación: Un movimiento de inventario pertenece a un usuario (quien lo registró).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
```
**¿Qué hace?** Define relación con el usuario que registró el movimiento.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Auditoría - saber quién hizo cada movimiento.

**Flujo de datos**:
```
inventario.id_usuario (FK) → usuarios.id (PK)
```

**Ejemplo de uso**:
```php
$movimiento = Inventario::find(1);

if ($movimiento->usuario) {
    echo "Registrado por: " . $movimiento->usuario->nombre;
} else {
    echo "Movimiento automático del sistema";
}
```

---

### 🟢 SECCIÓN 5: MÉTODO ESTÁTICO CRÍTICO - STOCK DISPONIBLE (Líneas 76-96)

```php
    /**
     * Calcular stock disponible de un producto.
     *
     * CRITICAL: Esta consulta debe ejecutarse en <500ms para tiempo real
     *
     * @param  int  $idProducto
     * @return int Stock disponible (puede ser negativo si hay inconsistencias)
     */
    public static function stockDisponible(int $idProducto): int
    {
```
**¿Qué hace?** Método estático para calcular stock actual de un producto.
**¿De dónde sale?** Lógica de negocio personalizada.
**¿Para qué sirve?** Usado en tiempo real en formularios de salidas.

**IMPORTANTE**: La palabra `static` significa que se llama sin instancia:
```php
// ✅ Correcto:
$stock = Inventario::stockDisponible(1);

// ❌ Incorrecto:
$inventario = new Inventario();
$stock = $inventario->stockDisponible(1); // Funciona pero es ineficiente
```

---

```php
        $entradas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'entrada')
            ->sum('cantidad');
```
**¿Qué hace?** Suma TODAS las entradas del producto.
**¿De dónde sale?** Consulta a tabla `inventario`.
**¿Para qué sirve?** Parte 1 del cálculo: total ingresado.

**SQL equivalente**:
```sql
SELECT SUM(cantidad) FROM inventario
WHERE id_producto = 1 AND tipo_movimiento = 'entrada'
```

**Ejemplo de datos**:
```
Producto: Botellones (id=1)
Entradas:
- Producción día 01/12: 500 unidades
- Producción día 02/12: 300 unidades
- Retorno de ruta: 50 unidades
Total entradas: 850
```

---

```php
        $salidas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');
```
**¿Qué hace?** Suma TODAS las salidas del producto.
**¿De dónde sale?** Consulta a tabla `inventario`.
**¿Para qué sirve?** Parte 2 del cálculo: total despachado.

**SQL equivalente**:
```sql
SELECT SUM(cantidad) FROM inventario
WHERE id_producto = 1 AND tipo_movimiento = 'salida'
```

**Ejemplo de datos**:
```
Producto: Botellones (id=1)
Salidas:
- Despacho día 01/12: 200 unidades
- Despacho día 02/12: 150 unidades
- Venta directa: 30 unidades
Total salidas: 380
```

---

```php
        $stock = (int) ($entradas - $salidas);
        return max(0, $stock); // No mostrar números negativos
    }
```
**¿Qué hace?** Calcula stock y asegura que no sea negativo.
**¿De dónde sale?** Lógica matemática: ENTRADAS - SALIDAS = STOCK.
**¿Para qué sirve?**
- Evitar mostrar valores negativos al usuario
- Protección contra inconsistencias en datos

**Ejemplo completo**:
```php
// Producto: Botellones (id=1)
// Entradas: 850
// Salidas: 380
// Stock: 850 - 380 = 470 ✅

// Caso inconsistente:
// Entradas: 100
// Salidas: 150
// Stock: 100 - 150 = -50
// Con max(0, $stock): retorna 0 (protección)
```

**⚠️ NOTA CRÍTICA**: Este método se ejecuta cada vez que se abre el formulario de salidas. Con miles de registros, puede ser lento. Ver sección de mejoras para optimización.

---

### 🟢 SECCIÓN 6: MÉTODOS ESTÁTICOS DE REGISTRO (Líneas 98-164)

```php
    /**
     * Registrar entrada de inventario con trazabilidad.
     *
     * @param  int|string  $idProducto
     * @param  int|string  $cantidad
     * @param  string|null  $observacion
     * @param  string|null  $origen
     * @param  string|null  $destino
     * @param  string|null  $referencia
     * @param  int|string|null  $idUsuario
     * @return self
     */
    public static function registrarEntrada(
        int|string $idProducto,
        int|string $cantidad,
        ?string $observacion = null,
        ?string $origen = null,
        ?string $destino = null,
        ?string $referencia = null,
        int|string|null $idUsuario = null
    ): self {
```
**¿Qué hace?** Método helper para crear entradas de inventario.
**¿De dónde sale?** Patrón Factory Method.
**¿Para qué sirve?** Simplificar creación de entradas desde otros controladores.

**Parámetros explicados**:
- `int|string`: Acepta ambos tipos (PHP 8+ Union Types)
- `?string`: El `?` significa nullable (puede ser null)
- `: self`: Retorna una instancia del mismo modelo

**¿Dónde se usa?**
- En `ProduccionDiariaController::store()` al registrar producción
- En `SalidasController::store()` al registrar retornos

---

```php
        return self::create([
            'id_producto' => (int) $idProducto,
            'tipo_movimiento' => 'entrada',
            'cantidad' => (int) $cantidad,
            'origen' => $origen,
            'destino' => $destino,
            'referencia' => $referencia,
            'id_usuario' => $idUsuario ? (int) $idUsuario : null,
            'fecha_movimiento' => now(),
            'observacion' => $observacion,
        ]);
    }
```
**¿Qué hace?** Crea el registro en la BD.
**¿De dónde sale?** Método `create()` de Eloquent.
**¿Para qué sirve?** Insertar entrada con trazabilidad completa.

**Conversiones importantes**:
- `(int) $idProducto`: Cast explícito a entero (seguridad)
- `now()`: Función helper de Laravel que retorna fecha/hora actual
- Operador ternario: `$idUsuario ? (int) $idUsuario : null` (si hay usuario lo convierte, sino null)

**Ejemplo de uso real en ProduccionDiariaController**:
```php
// Al registrar producción de 500 botellones
foreach ($request->productos as $productoData) {
    Inventario::registrarEntrada(
        idProducto: $productoData['id_producto'], // 1 (Botellones)
        cantidad: $productoData['cantidad'], // 500
        observacion: 'Producción diaria',
        origen: 'Planta de producción',
        destino: 'Inventario general',
        referencia: 'produccion-' . $produccion->id, // "produccion-123"
        idUsuario: auth()->id() // ID del usuario logueado
    );
}

// Resultado en BD:
// id_producto: 1
// tipo_movimiento: 'entrada'
// cantidad: 500
// origen: 'Planta de producción'
// destino: 'Inventario general'
// referencia: 'produccion-123'
// id_usuario: 5
// fecha_movimiento: 2025-12-02 10:30:00
```

---

```php
    /**
     * Registrar salida de inventario con trazabilidad.
     *
     * @param  int|string  $idProducto
     * @param  int|string  $cantidad
     * @param  string|null  $observacion
     * @param  string|null  $origen
     * @param  string|null  $destino
     * @param  string|null  $referencia
     * @param  int|string|null  $idUsuario
     * @return self
     */
    public static function registrarSalida(
        int|string $idProducto,
        int|string $cantidad,
        ?string $observacion = null,
        ?string $origen = null,
        ?string $destino = null,
        ?string $referencia = null,
        int|string|null $idUsuario = null
    ): self {
        return self::create([
            'id_producto' => (int) $idProducto,
            'tipo_movimiento' => 'salida',
            'cantidad' => (int) $cantidad,
            'origen' => $origen,
            'destino' => $destino,
            'referencia' => $referencia,
            'id_usuario' => $idUsuario ? (int) $idUsuario : null,
            'fecha_movimiento' => now(),
            'observacion' => $observacion,
        ]);
    }
```
**¿Qué hace?** Método helper para crear salidas de inventario.
**¿De dónde sale?** Patrón Factory Method (igual que registrarEntrada).
**¿Para qué sirve?** Simplificar creación de salidas desde SalidasController.

**Única diferencia con registrarEntrada()**: `'tipo_movimiento' => 'salida'`

**Ejemplo de uso real en SalidasController**:
```php
// Al despachar 200 botellones
foreach ($request->productos as $producto) {
    // 1. Validar stock
    $stockDisponible = Inventario::stockDisponible($producto['id_producto']);
    if ($stockDisponible < $producto['cantidad']) {
        return back()->withErrors('Stock insuficiente');
    }

    // 2. Registrar salida
    Inventario::registrarSalida(
        idProducto: $producto['id_producto'], // 1 (Botellones)
        cantidad: $producto['cantidad'], // 200
        observacion: 'Despacho Interno',
        origen: 'Inventario general',
        destino: 'Distribuidor: Juan Pérez',
        referencia: 'salida-' . $salida->id, // "salida-456"
        idUsuario: auth()->id()
    );

    // 3. Si hay retornos, registrarlos como ENTRADA
    if ($producto['retorno'] > 0) {
        Inventario::registrarEntrada(
            idProducto: $producto['id_producto'],
            cantidad: $producto['retorno'], // 10
            observacion: 'Retorno de ruta',
            origen: 'Distribuidor: Juan Pérez',
            destino: 'Inventario general',
            referencia: 'salida-' . $salida->id,
            idUsuario: auth()->id()
        );
    }
}
```

---

### 🟢 SECCIÓN 7: QUERY SCOPES (Líneas 166-196)

```php
    /**
     * Scope: Filtrar entradas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', 'entrada');
    }
```
**¿Qué hace?** Scope reutilizable para filtrar solo entradas.
**¿De dónde sale?** Característica de Eloquent (Query Scopes).
**¿Para qué sirve?** Evitar repetir `where('tipo_movimiento', 'entrada')` en todo el código.

**¿Cómo se usa?**
```php
// ❌ Sin scope (repetitivo):
$entradas1 = Inventario::where('tipo_movimiento', 'entrada')->get();
$entradas2 = Inventario::where('tipo_movimiento', 'entrada')->where('id_producto', 1)->get();

// ✅ Con scope (limpio):
$entradas1 = Inventario::entradas()->get();
$entradas2 = Inventario::entradas()->where('id_producto', 1)->get();

// ✅ Se puede combinar con otros scopes:
$entradas = Inventario::entradas()
    ->porRangoFechas('2025-12-01', '2025-12-31')
    ->with('producto')
    ->get();
```

**Nota**: El prefijo `scope` se omite al llamar. `scopeEntradas` → `entradas()`.

---

```php
    /**
     * Scope: Filtrar salidas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', 'salida');
    }
```
**¿Qué hace?** Scope reutilizable para filtrar solo salidas.
**¿De dónde sale?** Eloquent Query Scopes.
**¿Para qué sirve?** Consultas limpias de salidas.

**Ejemplo**:
```php
// Ver todas las salidas del mes
$salidas = Inventario::salidas()
    ->whereMonth('fecha_movimiento', 12)
    ->with(['producto', 'usuario'])
    ->orderBy('fecha_movimiento', 'desc')
    ->get();

// Agrupar salidas por producto
$salidasPorProducto = Inventario::salidas()
    ->selectRaw('id_producto, SUM(cantidad) as total')
    ->groupBy('id_producto')
    ->get();
```

---

```php
    /**
     * Scope: Filtrar por rango de fechas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     */
    public function scopePorRangoFechas($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }
}
```
**¿Qué hace?** Scope para filtrar movimientos por rango de fechas.
**¿De dónde sale?** Eloquent Query Scopes con parámetros.
**¿Para qué sirve?** Reportes y consultas por período.

**¿Cómo se usa?**
```php
// Movimientos de diciembre 2025
$movimientos = Inventario::porRangoFechas('2025-12-01', '2025-12-31')->get();

// Entradas de la última semana
$entradas = Inventario::entradas()
    ->porRangoFechas(
        now()->subWeek()->toDateString(),
        now()->toDateString()
    )
    ->get();

// Salidas del trimestre con productos
$salidas = Inventario::salidas()
    ->porRangoFechas('2025-10-01', '2025-12-31')
    ->with('producto')
    ->orderBy('fecha_movimiento', 'asc')
    ->get();
```

**SQL equivalente**:
```sql
SELECT * FROM inventario
WHERE fecha_movimiento BETWEEN '2025-12-01' AND '2025-12-31'
```

---

# USUARIO.PHP

**Ubicación**: `app/Models/Usuario.php`
**Líneas totales**: 204
**Complejidad**: Media-Alta
**Propósito**: Autenticación, autorización y gestión de usuarios del sistema

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DECLARACIONES Y NAMESPACE (Líneas 1-12)

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\CanResetPassword;
```
**¿Qué hace?** Importa clases necesarias para autenticación.
**¿De dónde sale?** Framework Laravel.
**¿Para qué sirve?**
- `Authenticatable`: Clase base para usuarios autenticables (en lugar de `Model`)
- `Notifiable`: Permite enviar notificaciones (emails, SMS)
- `Hash`: Para hashear contraseñas (bcrypt)
- `CanResetPassword`: Interface para resetear contraseña

**Diferencia clave**:
```php
// ❌ Modelo normal:
class Producto extends Model { }

// ✅ Modelo de usuario:
class Usuario extends Authenticatable { }
```

---

### 🟢 SECCIÓN 2: DOCUMENTACIÓN PHPDOC (Líneas 13-28)

```php
/**
 * Modelo de usuario del sistema con autenticación completa.
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property int $id_rol
 * @property string $estado (activo|inactivo)
 * @property \Carbon\Carbon|null $ultimo_acceso
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Rol $rol
 * @property-read Personal|null $personal
 */
```
**¿Qué hace?** Documenta propiedades del usuario.
**¿De dónde sale?** Tabla `usuarios` en BD.
**¿Para qué sirve?** Autocompletado en IDEs y documentación.

**Propiedades importantes**:
- `password`: Hasheada con bcrypt (nunca se guarda en texto plano)
- `remember_token`: Para "Recordarme" en login
- `ultimo_acceso`: Auditoría de sesiones
- `estado`: Para desactivar usuarios sin eliminarlos

---

### 🟢 SECCIÓN 3: CLASE Y CONFIGURACIÓN (Líneas 29-67)

```php
class Usuario extends Authenticatable implements CanResetPassword
{
    use Notifiable;
```
**¿Qué hace?** Define clase Usuario con traits de notificaciones.
**¿De dónde sale?** Laravel Authentication System.
**¿Para qué sirve?**
- `extends Authenticatable`: Permite login/logout
- `implements CanResetPassword`: Permite recuperar contraseña
- `use Notifiable`: Habilita método `$usuario->notify()`

---

```php
    protected $table = 'usuarios';
```
**¿Qué hace?** Especifica tabla en BD.
**¿De dónde sale?** Configuración manual.
**¿Para qué sirve?** Laravel buscaría `users` por defecto (inglés), esto lo corrige.

---

```php
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'id_rol',
        'id_personal',
        'estado',
        'ultimo_acceso',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Permite crear usuarios con `Usuario::create()`.

**Ejemplo**:
```php
// ✅ Funciona:
Usuario::create([
    'nombre' => 'Juan Pérez',
    'email' => 'juan@aguacolegial.com',
    'password' => 'secreto123',
    'id_rol' => 2,
    'estado' => 'activo'
]);

// ❌ No funciona (protegido):
Usuario::create([
    'id' => 999, // Ignorado
    'remember_token' => 'abc123' // Ignorado
]);
```

---

```php
    protected $hidden = [
        'password',
        'remember_token',
    ];
```
**¿Qué hace?** Oculta campos al serializar a JSON/Array.
**¿De dónde sale?** Característica de Eloquent.
**¿Para qué sirve?** Seguridad - no exponer contraseñas en APIs.

**Ejemplo**:
```php
$usuario = Usuario::find(1);

// ❌ Sin $hidden:
return $usuario->toJson();
// {"id":1,"nombre":"Juan","password":"$2y$10$abc...","remember_token":"xyz..."}

// ✅ Con $hidden:
return $usuario->toJson();
// {"id":1,"nombre":"Juan","email":"juan@aguacolegial.com"}
```

---

```php
    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];
```
**¿Qué hace?** Convierte campos a objetos Carbon.
**¿De dónde sale?** Eloquent Casting.
**¿Para qué sirve?** Manipular fechas fácilmente.

**Ejemplo**:
```php
$usuario = Usuario::find(1);

// Verificar si el usuario accedió hoy
if ($usuario->ultimo_acceso->isToday()) {
    echo "Usuario activo hoy";
}

// Ver hace cuánto fue su último acceso
echo $usuario->ultimo_acceso->diffForHumans(); // "hace 3 horas"

// Formato personalizado
echo $usuario->created_at->format('d/m/Y H:i'); // "01/12/2025 10:30"
```

---

### 🟢 SECCIÓN 4: RELACIONES (Líneas 69-83)

```php
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
```
**¿Qué hace?** Relación con modelo Rol.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Obtener permisos del usuario.

**Flujo de datos**:
```
usuarios.id_rol (FK) → roles.id (PK)
```

**Ejemplo**:
```php
$usuario = Usuario::find(1);

// Obtener rol del usuario
echo $usuario->rol->nombre; // "admin", "produccion", etc.

// Verificar permisos
if ($usuario->rol->nombre === 'admin') {
    // Permitir acceso
}

// Eager loading
$usuarios = Usuario::with('rol')->get();
```

---

```php
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }
```
**¿Qué hace?** Relación con modelo Personal.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Vincular usuario con su registro de empleado.

**Flujo de datos**:
```
usuarios.id_personal (FK) → personal.id (PK)
```

**¿Por qué existe esta relación?**
- No todos los empleados tienen acceso al sistema
- Un empleado (Personal) puede o no tener cuenta de usuario (Usuario)
- Si tiene cuenta, `id_personal` vincula ambos registros

**Ejemplo**:
```php
$usuario = Usuario::find(1);

// Obtener datos del empleado
echo $usuario->personal->nombres; // "Juan"
echo $usuario->personal->apellidos; // "Pérez"
echo $usuario->personal->puesto; // "Supervisor de producción"

// Verificar si es chofer
if ($usuario->personal->puesto === 'Chofer') {
    // Permitir acceso a módulo de rutas
}
```

---

### 🟢 SECCIÓN 5: MÉTODOS DE VERIFICACIÓN (Líneas 85-117)

```php
    public function tieneRol(string $nombreRol): bool
    {
        return $this->rol && $this->rol->nombre === $nombreRol;
    }
```
**¿Qué hace?** Verifica si el usuario tiene un rol específico.
**¿De dónde sale?** Método personalizado.
**¿Para qué sirve?** Control de acceso en controladores y vistas.

**Ejemplo de uso**:
```php
// En controlador
public function index()
{
    if (!auth()->user()->tieneRol('admin')) {
        abort(403, 'No autorizado');
    }
    // ...
}

// En vista Blade
@if(auth()->user()->tieneRol('produccion'))
    <a href="{{ route('produccion.create') }}">Registrar producción</a>
@endif
```

---

```php
    public function esAdmin(): bool
    {
        return $this->tieneRol('admin');
    }
```
**¿Qué hace?** Verifica si el usuario es administrador.
**¿De dónde sale?** Helper personalizado.
**¿Para qué sirve?** Simplificar verificación de permisos.

**Ejemplo**:
```php
// ❌ Sin helper (más código):
if (auth()->user()->rol && auth()->user()->rol->nombre === 'admin') {
    // ...
}

// ✅ Con helper (limpio):
if (auth()->user()->esAdmin()) {
    // ...
}

// En middleware
public function handle($request, Closure $next)
{
    if (!$request->user()->esAdmin()) {
        return redirect()->route('dashboard');
    }
    return $next($request);
}
```

---

```php
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }
```
**¿Qué hace?** Verifica si el usuario está activo.
**¿De dónde sale?** Helper personalizado.
**¿Para qué sirve?** Prevenir login de usuarios desactivados.

**Ejemplo en middleware de autenticación**:
```php
public function handle($request, Closure $next)
{
    if (auth()->check() && !auth()->user()->estaActivo()) {
        auth()->logout();
        return redirect()->route('login')
            ->withErrors('Tu cuenta ha sido desactivada');
    }
    return $next($request);
}
```

---

```php
    public function nombreRol(): string
    {
        return $this->rol ? $this->rol->nombre : 'sin_rol';
    }
```
**¿Qué hace?** Obtiene nombre del rol de forma segura.
**¿De dónde sale?** Helper personalizado.
**¿Para qué sirve?** Evitar error si usuario no tiene rol asignado.

**Ejemplo**:
```php
// ❌ Sin helper (puede causar error):
echo $usuario->rol->nombre; // Error si $usuario->rol es null

// ✅ Con helper (seguro):
echo $usuario->nombreRol(); // Retorna 'sin_rol' si no hay rol
```

---

### 🟢 SECCIÓN 6: QUERY SCOPES (Líneas 119-140)

```php
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
```
**¿Qué hace?** Scope para filtrar usuarios activos.
**¿De dónde sale?** Eloquent Query Scopes.
**¿Para qué sirve?** Consultas reutilizables.

**Ejemplo**:
```php
// Obtener solo usuarios activos
$usuariosActivos = Usuario::activos()->get();

// Combinar con otros filtros
$adminsActivos = Usuario::activos()
    ->porRol('admin')
    ->get();

// En select de formulario
$usuarios = Usuario::activos()
    ->orderBy('nombre')
    ->pluck('nombre', 'id');
```

---

```php
    public function scopePorRol($query, string $nombreRol)
    {
        return $query->whereHas('rol', function ($q) use ($nombreRol) {
            $q->where('nombre', $nombreRol);
        });
    }
```
**¿Qué hace?** Scope para filtrar usuarios por rol.
**¿De dónde sale?** Eloquent Query Scopes con relaciones.
**¿Para qué sirve?** Consultas complejas de roles.

**¿Qué es `whereHas`?**
- Filtra el modelo padre (Usuario) basándose en condición del modelo hijo (Rol)
- Solo retorna usuarios cuyo rol cumple la condición

**SQL equivalente**:
```sql
SELECT usuarios.* FROM usuarios
INNER JOIN roles ON usuarios.id_rol = roles.id
WHERE roles.nombre = 'admin'
```

**Ejemplo**:
```php
// Obtener todos los supervisores
$supervisores = Usuario::porRol('produccion')->get();

// Combinar múltiples filtros
$adminisActivos = Usuario::activos()
    ->porRol('admin')
    ->with('personal')
    ->get();
```

---

### 🟢 SECCIÓN 7: MUTATORS Y ACCESSORS (Líneas 142-159)

```php
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
```
**¿Qué hace?** Mutator que hashea automáticamente la contraseña.
**¿De dónde sale?** Eloquent Mutators.
**¿Para qué sirve?** Seguridad - nunca guardar contraseñas en texto plano.

**¿Qué hace `Hash::needsRehash()`?**
- Verifica si el valor ya está hasheado
- Si ya está hasheado: No hace nada (evita doble hash)
- Si es texto plano: Lo hashea con bcrypt

**Ejemplo**:
```php
// ✅ Al crear usuario:
$usuario = Usuario::create([
    'nombre' => 'Juan',
    'email' => 'juan@example.com',
    'password' => 'secreto123' // Texto plano
]);
// BD: password = "$2y$10$abcdefg..." (hasheado automáticamente)

// ✅ Al actualizar:
$usuario->password = 'nueva_contraseña'; // Texto plano
$usuario->save();
// BD: password = "$2y$10$xyz..." (hasheado automáticamente)

// ⚠️ Si ya viene hasheado (ej: desde seeder):
$usuario->password = Hash::make('contraseña'); // Ya hasheado
$usuario->save();
// BD: password = "$2y$10$..." (NO se vuelve a hashear)
```

---

```php
    public function actualizarUltimoAcceso(): void
    {
        $this->ultimo_acceso = now();
        $this->save();
    }
```
**¿Qué hace?** Actualiza timestamp de último acceso.
**¿De dónde sale?** Método personalizado.
**¿Para qué sirve?** Auditoría de sesiones.

**¿Dónde se llama?**
En un middleware después del login:
```php
// App\Http\Middleware\TrackUserActivity.php
public function handle($request, Closure $next)
{
    if (auth()->check()) {
        auth()->user()->actualizarUltimoAcceso();
    }
    return $next($request);
}
```

**Ejemplo de uso en reportes**:
```php
// Usuarios que no han accedido en 30 días
$usuariosInactivos = Usuario::where('ultimo_acceso', '<', now()->subDays(30))
    ->activos()
    ->get();

// Enviar recordatorio
foreach ($usuariosInactivos as $usuario) {
    $usuario->notify(new RecordatorioAccesoNotification());
}
```

---

### 🟢 SECCIÓN 8: MÉTODOS DE AUTENTICACIÓN (Líneas 161-203)

```php
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }
```
**¿Qué hace?** Define el nombre del campo identificador único.
**¿De dónde sale?** Interface `Authenticatable`.
**¿Para qué sirve?** Para que `auth()->id()` funcione correctamente.

**IMPORTANTE**:
- Este método retorna `'id'` (el nombre de la columna que identifica al usuario)
- NO retorna el valor del ID
- Laravel usa esto para saber qué campo consultar en la tabla

**Ejemplo interno de Laravel**:
```php
// Cuando haces:
$userId = auth()->id();

// Laravel internamente hace:
$identifierName = $usuario->getAuthIdentifierName(); // "id"
$userId = $usuario->$identifierName; // $usuario->id
```

---

```php
    public function username(): string
    {
        return 'email';
    }
```
**¿Qué hace?** Define el campo usado para login.
**¿De dónde sale?** Método personalizado.
**¿Para qué sirve?** Especificar que se usa email (no username) para autenticar.

**¿Dónde se usa?**
En el controlador de autenticación:
```php
// App\Http\Controllers\Auth\LoginController.php
protected function credentials(Request $request)
{
    return [
        $this->username() => $request->email, // 'email' => 'juan@example.com'
        'password' => $request->password
    ];
}
```

---

```php
    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }
```
**¿Qué hace?** Define el email para recuperar contraseña.
**¿De dónde sale?** Interface `CanResetPassword`.
**¿Para qué sirve?** Enviar link de reseteo de contraseña.

**Flujo de recuperación de contraseña**:
1. Usuario hace clic en "¿Olvidaste tu contraseña?"
2. Ingresa su email
3. Laravel llama a `getEmailForPasswordReset()` para obtener el email
4. Envía notificación con link de reseteo
5. Usuario hace clic en link y cambia contraseña

---

```php
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
```
**¿Qué hace?** Envía notificación de reseteo de contraseña.
**¿De dónde sale?** Interface `CanResetPassword`.
**¿Para qué sirve?** Personalizar el email de recuperación.

**¿Qué es `$token`?**
- Token único generado por Laravel
- Válido por 60 minutos (configurable)
- Se usa en el link: `https://aguacolegial.com/reset-password/{token}`

**Ejemplo de notificación personalizada**:
```php
// App\Notifications\ResetPasswordNotification.php
class ResetPasswordNotification extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recuperar contraseña - Agua Colegial')
            ->line('Recibimos una solicitud para resetear tu contraseña.')
            ->action('Cambiar contraseña', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Este link expira en 60 minutos.')
            ->line('Si no solicitaste esto, ignora este email.');
    }
}
```

---

# ROL.PHP

**Ubicación**: `app/Models/Rol.php`
**Líneas totales**: 56
**Complejidad**: Baja
**Propósito**: Definir roles del sistema y sus relaciones

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DOCUMENTACIÓN Y CLASE (Líneas 1-30)

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de roles del sistema.
 *
 * Roles disponibles:
 * - admin: Acceso total al sistema
 * - produccion: Módulo de producción
 * - inventario: Módulo de inventario
 * - despacho: Módulo de despachos
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $observacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Rol extends Model
{
    protected $table = 'roles';
```
**¿Qué hace?** Define modelo Rol con documentación de roles del sistema.
**¿De dónde sale?** Tabla `roles` en BD.
**¿Para qué sirve?** Gestionar permisos y accesos.

**Roles del sistema**:
1. **admin**: Acceso total (CRUD de todo)
2. **produccion**: Solo módulo de producción y asistencias
3. **inventario**: Solo módulo de inventario e insumos
4. **despacho**: Solo módulo de salidas y vehículos

---

### 🟢 SECCIÓN 2: CONFIGURACIÓN (Líneas 31-38)

```php
    protected $fillable = [
        'nombre',
        'observacion',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Crear roles con `Rol::create()`.

**Ejemplo**:
```php
// Crear un nuevo rol
Rol::create([
    'nombre' => 'ventas',
    'observacion' => 'Acceso a módulo de ventas y reportes'
]);

// Usar en seeder
// database/seeders/RolesSeeder.php
public function run()
{
    $roles = [
        ['nombre' => 'admin', 'observacion' => 'Administrador del sistema'],
        ['nombre' => 'produccion', 'observacion' => 'Supervisor de producción'],
        ['nombre' => 'inventario', 'observacion' => 'Encargado de inventario'],
        ['nombre' => 'despacho', 'observacion' => 'Responsable de despachos'],
    ];

    foreach ($roles as $rol) {
        Rol::create($rol);
    }
}
```

---

### 🟢 SECCIÓN 3: RELACIONES (Líneas 40-46)

```php
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }
```
**¿Qué hace?** Define relación uno-a-muchos con usuarios.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Obtener todos los usuarios de un rol.

**Flujo de datos**:
```
roles.id (PK) ← usuarios.id_rol (FK)
```

**Ejemplo**:
```php
// Obtener rol
$rolAdmin = Rol::where('nombre', 'admin')->first();

// Ver cuántos usuarios tienen este rol
echo $rolAdmin->usuarios->count(); // 3

// Listar usuarios del rol
foreach ($rolAdmin->usuarios as $usuario) {
    echo $usuario->nombre . " - " . $usuario->email;
}

// Eager loading
$roles = Rol::with('usuarios')->get();
foreach ($roles as $rol) {
    echo $rol->nombre . ": " . $rol->usuarios->count() . " usuarios";
}

// Filtrar usuarios activos del rol
$adminsActivos = $rolAdmin->usuarios()
    ->where('estado', 'activo')
    ->get();
```

---

### 🟢 SECCIÓN 4: MÉTODOS HELPERS (Líneas 48-55)

```php
    public function esAdmin(): bool
    {
        return $this->nombre === 'admin';
    }
}
```
**¿Qué hace?** Verifica si el rol es administrador.
**¿De dónde sale?** Helper personalizado.
**¿Para qué sirve?** Simplificar verificaciones de permisos.

**Ejemplo**:
```php
$rol = Rol::find(1);

// ❌ Sin helper:
if ($rol->nombre === 'admin') {
    // ...
}

// ✅ Con helper:
if ($rol->esAdmin()) {
    // ...
}

// Uso en Usuario model
public function esAdmin(): bool
{
    return $this->rol && $this->rol->esAdmin();
}

// Uso en middleware
public function handle($request, Closure $next)
{
    if (!$request->user()->rol->esAdmin()) {
        abort(403);
    }
    return $next($request);
}
```

---

## 📊 RESUMEN DE FUNCIONALIDADES

### Inventario.php
| Funcionalidad | Descripción | Método/Propiedad |
|---------------|-------------|------------------|
| Registro de movimientos | Entradas y salidas con trazabilidad | `registrarEntrada()`, `registrarSalida()` |
| Cálculo de stock | Stock en tiempo real por producto | `stockDisponible()` |
| Filtros reutilizables | Scopes para consultas | `entradas()`, `salidas()`, `porRangoFechas()` |
| Relaciones | Producto y usuario del movimiento | `producto()`, `usuario()` |
| Auditoría | Referencia única por movimiento | Campo `referencia` |

### Usuario.php
| Funcionalidad | Descripción | Método/Propiedad |
|---------------|-------------|------------------|
| Autenticación | Login/logout del sistema | Hereda de `Authenticatable` |
| Gestión de contraseñas | Hash automático | `setPasswordAttribute()` |
| Roles y permisos | Verificación de accesos | `tieneRol()`, `esAdmin()` |
| Recuperar contraseña | Envío de email de reseteo | `sendPasswordResetNotification()` |
| Auditoría de sesiones | Tracking de último acceso | `actualizarUltimoAcceso()` |
| Estados | Activar/desactivar usuarios | `estaActivo()` |

### Rol.php
| Funcionalidad | Descripción | Método/Propiedad |
|---------------|-------------|------------------|
| Definición de roles | 4 roles del sistema | `admin`, `produccion`, `inventario`, `despacho` |
| Relación con usuarios | Listar usuarios por rol | `usuarios()` |
| Verificación de permisos | Check de rol admin | `esAdmin()` |

---

## 🗄️ TABLAS DE BASE DE DATOS

### Tabla: `inventario`

```sql
CREATE TABLE inventario (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_producto BIGINT UNSIGNED NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    origen VARCHAR(255) NULL,
    destino VARCHAR(255) NULL,
    referencia VARCHAR(255) NULL COMMENT 'Ej: produccion-123, salida-456',
    id_usuario BIGINT UNSIGNED NULL,
    fecha_movimiento DATETIME NOT NULL,
    observacion TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL,

    INDEX idx_id_producto (id_producto),
    INDEX idx_tipo_movimiento (tipo_movimiento),
    INDEX idx_fecha_movimiento (fecha_movimiento),
    INDEX idx_referencia (referencia)
);
```

**Índices importantes**:
- `idx_id_producto`: Acelera `stockDisponible()`
- `idx_tipo_movimiento`: Acelera scopes `entradas()` y `salidas()`
- `idx_fecha_movimiento`: Acelera `porRangoFechas()`
- `idx_referencia`: Acelera búsqueda de movimientos por registro origen

**Datos de ejemplo**:
```
| id | id_producto | tipo_movimiento | cantidad | origen               | destino           | referencia       | fecha_movimiento    |
|----|-------------|-----------------|----------|----------------------|-------------------|------------------|---------------------|
| 1  | 1           | entrada         | 500      | Producción diaria    | Inventario general| produccion-10    | 2025-12-01 08:00:00 |
| 2  | 1           | salida          | 200      | Inventario general   | Dist. Juan Pérez  | salida-5         | 2025-12-01 10:00:00 |
| 3  | 1           | entrada         | 10       | Dist. Juan Pérez     | Inventario general| salida-5         | 2025-12-01 18:00:00 |
| 4  | 3           | entrada         | 300      | Producción diaria    | Inventario general| produccion-11    | 2025-12-02 08:00:00 |
| 5  | 3           | salida          | 150      | Inventario general   | Cliente directo   | salida-6         | 2025-12-02 11:00:00 |
```

**Cálculo de stock para Botellones (id=1)**:
```
Entradas: 500 + 10 = 510
Salidas: 200
Stock: 510 - 200 = 310 botellones disponibles
```

---

### Tabla: `usuarios`

```sql
CREATE TABLE usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_rol BIGINT UNSIGNED NOT NULL,
    id_personal BIGINT UNSIGNED NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    ultimo_acceso DATETIME NULL,
    remember_token VARCHAR(100) NULL,
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_personal) REFERENCES personal(id) ON DELETE CASCADE,

    INDEX idx_email (email),
    INDEX idx_estado (estado),
    INDEX idx_id_rol (id_rol)
);
```

**Datos de ejemplo**:
```
| id | nombre         | email                     | id_rol | id_personal | estado  | ultimo_acceso       |
|----|----------------|---------------------------|--------|-------------|---------|---------------------|
| 1  | Admin Sistema  | admin@aguacolegial.com    | 1      | NULL        | activo  | 2025-12-02 09:00:00 |
| 2  | Juan Pérez     | juan@aguacolegial.com     | 2      | 5           | activo  | 2025-12-01 14:30:00 |
| 3  | María López    | maria@aguacolegial.com    | 3      | 8           | activo  | 2025-12-02 08:15:00 |
| 4  | Carlos Ruiz    | carlos@aguacolegial.com   | 4      | 12          | inactivo| 2025-11-15 10:00:00 |
```

---

### Tabla: `roles`

```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    observacion TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Datos de ejemplo**:
```
| id | nombre      | observacion                          |
|----|-------------|--------------------------------------|
| 1  | admin       | Administrador con acceso total       |
| 2  | produccion  | Supervisor de producción             |
| 3  | inventario  | Encargado de inventario e insumos    |
| 4  | despacho    | Responsable de salidas y despachos   |
```

---

## 🔄 FLUJOS DE TRABAJO

### Flujo 1: Registro de Producción con Inventario

```
[ProduccionDiariaController::store()]
    ↓
1. Usuario registra producción:
   - 500 botellones
   - 300 aguas naturales
   - Responsable: Juan Pérez
    ↓
2. Controller crea registro en control_produccion_diaria
    ↓
3. Controller llama a Inventario::registrarEntrada() por cada producto:
    ↓
[Inventario::registrarEntrada()]
    ↓
4. Se crean 2 registros en tabla inventario:
   - Producto: Botellones, cantidad: 500, tipo: entrada
   - Producto: Agua natural, cantidad: 300, tipo: entrada
    ↓
5. Stock actualizado automáticamente:
   - Botellones: 310 → 810
   - Agua natural: 150 → 450
```

**Código del controller**:
```php
// ProduccionDiariaController::store()
DB::beginTransaction();
try {
    // 1. Crear registro maestro
    $produccion = ProduccionDiaria::create([...]);

    // 2. Registrar productos y actualizar inventario
    foreach ($request->productos as $productoData) {
        // Insertar en control_produccion_productos
        ProduccionProducto::create([...]);

        // Insertar en inventario (entrada)
        Inventario::registrarEntrada(
            idProducto: $productoData['id_producto'],
            cantidad: $productoData['cantidad'],
            observacion: 'Producción diaria',
            origen: 'Planta de producción',
            destino: 'Inventario general',
            referencia: 'produccion-' . $produccion->id,
            idUsuario: auth()->id()
        );
    }

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

---

### Flujo 2: Despacho con Validación de Stock

```
[SalidasController::store()]
    ↓
1. Usuario intenta despachar 200 botellones
    ↓
2. Controller verifica stock disponible:
    ↓
[Inventario::stockDisponible(1)]
    ↓
3. Consulta:
   - Entradas: 810
   - Salidas: 500
   - Stock: 310 ✅ (suficiente)
    ↓
4. SI stock >= 200:
     ↓
   4.1. Crear registro en control_salidas_productos
     ↓
   4.2. Llamar Inventario::registrarSalida()
     ↓
   4.3. Stock actualizado: 310 → 110
     ↓
   4.4. Si hay retornos (10 botellones):
        Llamar Inventario::registrarEntrada()
        Stock: 110 → 120
     ↓
5. SI stock < 200:
     ↓
   5.1. Retornar error: "Stock insuficiente"
   5.2. No crear ningún registro
```

**Código del controller**:
```php
// SalidasController::store()
DB::beginTransaction();
try {
    // 1. Validar stock antes de guardar
    foreach ($request->productos as $producto) {
        $stockDisponible = Inventario::stockDisponible($producto['id_producto']);

        if ($stockDisponible < $producto['cantidad']) {
            return back()->withErrors([
                'error' => "Stock insuficiente para {$producto['nombre']}.
                           Disponible: {$stockDisponible},
                           Solicitado: {$producto['cantidad']}"
            ]);
        }
    }

    // 2. Crear salida
    $salida = SalidaProducto::create([...]);

    // 3. Registrar movimientos de inventario
    foreach ($request->productos as $producto) {
        // 3.1. Registrar salida
        Inventario::registrarSalida(
            idProducto: $producto['id_producto'],
            cantidad: $producto['cantidad'],
            observacion: $request->tipo_salida,
            origen: 'Inventario general',
            destino: $request->distribuidor ?? $request->cliente,
            referencia: 'salida-' . $salida->id,
            idUsuario: auth()->id()
        );

        // 3.2. Registrar retorno (si hay)
        if ($producto['retorno'] > 0) {
            Inventario::registrarEntrada(
                idProducto: $producto['id_producto'],
                cantidad: $producto['retorno'],
                observacion: 'Retorno de ruta',
                origen: $request->distribuidor ?? $request->cliente,
                destino: 'Inventario general',
                referencia: 'salida-' . $salida->id,
                idUsuario: auth()->id()
            );
        }
    }

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

---

### Flujo 3: Login de Usuario

```
[LoginController]
    ↓
1. Usuario ingresa email y contraseña
    ↓
2. Laravel busca usuario por email
    ↓
[Usuario::where('email', $email)->first()]
    ↓
3. Verifica contraseña hasheada
    ↓
[Hash::check($password, $usuario->password)]
    ↓
4. SI contraseña correcta:
     ↓
   4.1. Verificar si usuario está activo
        ↓
   [Usuario::estaActivo()]
        ↓
   4.2. SI activo:
          ↓
        4.2.1. Crear sesión
        4.2.2. Actualizar último acceso
               ↓
        [Usuario::actualizarUltimoAcceso()]
        4.2.3. Redirigir a dashboard
     ↓
   4.3. SI inactivo:
        Logout y mostrar error
     ↓
5. SI contraseña incorrecta:
   Retornar error de credenciales
```

**Código del controller**:
```php
// LoginController::login()
public function login(Request $request)
{
    // 1. Validar datos
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 2. Buscar usuario
    $usuario = Usuario::where('email', $request->email)->first();

    // 3. Verificar contraseña
    if (!$usuario || !Hash::check($request->password, $usuario->password)) {
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    // 4. Verificar estado
    if (!$usuario->estaActivo()) {
        return back()->withErrors(['email' => 'Usuario desactivado']);
    }

    // 5. Crear sesión
    auth()->login($usuario, $request->remember);

    // 6. Actualizar último acceso
    $usuario->actualizarUltimoAcceso();

    // 7. Redirigir según rol
    if ($usuario->esAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('dashboard');
    }
}
```

---

### Flujo 4: Verificación de Permisos en Controlador

```
[Middleware CheckRole]
    ↓
1. Usuario intenta acceder a ruta protegida
   Ej: /control/produccion/create
    ↓
2. Middleware verifica autenticación
    ↓
[auth()->check()]
    ↓
3. SI autenticado:
     ↓
   3.1. Obtener usuario y rol
        ↓
   [auth()->user()->rol]
        ↓
   3.2. Verificar permisos
        ↓
   [Usuario::tieneRol('produccion') OR Usuario::esAdmin()]
        ↓
   3.3. SI tiene permiso:
        Continuar a controlador
        ↓
   3.4. SI NO tiene permiso:
        Abort 403 (No autorizado)
     ↓
4. SI NO autenticado:
   Redirigir a login
```

**Código del middleware**:
```php
// App\Http\Middleware\CheckRole.php
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // 1. Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Admin tiene acceso a todo
        if (auth()->user()->esAdmin()) {
            return $next($request);
        }

        // 3. Verificar rol específico
        $usuario = auth()->user();
        foreach ($roles as $rol) {
            if ($usuario->tieneRol($rol)) {
                return $next($request);
            }
        }

        // 4. Sin permiso
        abort(403, 'No tienes permiso para acceder a esta sección');
    }
}
```

**Uso en rutas**:
```php
// routes/web.php
Route::middleware(['auth', 'role:produccion'])->group(function () {
    Route::get('/control/produccion', [ProduccionController::class, 'index']);
    Route::post('/control/produccion', [ProduccionController::class, 'store']);
});

Route::middleware(['auth', 'role:admin,inventario'])->group(function () {
    Route::resource('/control/insumos', InsumosController::class);
});
```

---

## 🔗 COMUNICACIÓN ENTRE MODELOS

### Diagrama de Relaciones

```
┌─────────────────┐
│     Producto    │
│  (productos)    │
└────────┬────────┘
         │ 1
         │
         │ N
┌────────▼─────────┐       ┌──────────────┐
│   Inventario     │───N──▶│   Usuario    │
│  (inventario)    │       │  (usuarios)  │
└──────────────────┘       └──────┬───────┘
         ▲                         │ N
         │                         │
         │                         │ 1
         │                  ┌──────▼───────┐
         │                  │      Rol     │
         │                  │   (roles)    │
         │                  └──────────────┘
         │
         │
┌────────┴──────────────────────────────┐
│                                        │
│  ProduccionDiaria    SalidaProducto   │
│  (produce entradas)  (produce salidas) │
│                                        │
└────────────────────────────────────────┘
```

### Flujo de Datos: Producción → Inventario

```php
// 1. ProduccionDiariaController crea producción
$produccion = ProduccionDiaria::create([...]);

// 2. Por cada producto, llama a Inventario
foreach ($productos as $prod) {
    Inventario::registrarEntrada(
        idProducto: $prod['id_producto'], // Referencia a Producto
        cantidad: $prod['cantidad'],
        referencia: 'produccion-' . $produccion->id,
        idUsuario: auth()->id() // Referencia a Usuario
    );
}

// 3. Inventario se relaciona con Producto y Usuario
$movimiento = Inventario::with(['producto', 'usuario'])->first();
echo $movimiento->producto->nombre_producto; // "Botellones"
echo $movimiento->usuario->nombre; // "Juan Pérez"
```

### Flujo de Datos: Usuario → Rol → Permisos

```php
// 1. Usuario se autentica
$usuario = Usuario::find(auth()->id());

// 2. Usuario tiene un rol
echo $usuario->rol->nombre; // "produccion"

// 3. Rol define permisos
if ($usuario->tieneRol('produccion')) {
    // Acceso a módulo de producción
}

// 4. Verificar usuarios de un rol
$rolProduccion = Rol::where('nombre', 'produccion')->first();
foreach ($rolProduccion->usuarios as $usuario) {
    echo $usuario->nombre;
}
```

### Flujo de Datos: Salida → Inventario → Stock

```php
// 1. SalidasController valida stock
$stock = Inventario::stockDisponible($idProducto);

// 2. Si OK, crea salida
$salida = SalidaProducto::create([...]);

// 3. Registra movimiento de inventario
Inventario::registrarSalida(
    idProducto: $idProducto,
    cantidad: $cantidad,
    referencia: 'salida-' . $salida->id
);

// 4. Inventario actualizado automáticamente
$nuevoStock = Inventario::stockDisponible($idProducto);
// Stock anterior: 310
// Salida: 200
// Nuevo stock: 110
```

---

## ✅ TODOS Y MEJORAS FUTURAS

### Inventario.php

#### TODO 1: Optimizar método `stockDisponible()`
**Problema**: Con miles de registros, `sum()` puede ser lento (>1 segundo).
**Solución**: Crear tabla `stock_actual` con triggers:

```sql
CREATE TABLE stock_actual (
    id_producto BIGINT UNSIGNED PRIMARY KEY,
    stock INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Trigger al insertar en inventario
DELIMITER $$
CREATE TRIGGER actualizar_stock_insert
AFTER INSERT ON inventario
FOR EACH ROW
BEGIN
    IF NEW.tipo_movimiento = 'entrada' THEN
        INSERT INTO stock_actual (id_producto, stock)
        VALUES (NEW.id_producto, NEW.cantidad)
        ON DUPLICATE KEY UPDATE stock = stock + NEW.cantidad;
    ELSE
        INSERT INTO stock_actual (id_producto, stock)
        VALUES (NEW.id_producto, -NEW.cantidad)
        ON DUPLICATE KEY UPDATE stock = stock - NEW.cantidad;
    END IF;
END$$
DELIMITER ;
```

**Nueva implementación**:
```php
public static function stockDisponible(int $idProducto): int
{
    // ✅ Consulta instantánea (1 registro en lugar de miles)
    $stock = DB::table('stock_actual')
        ->where('id_producto', $idProducto)
        ->value('stock') ?? 0;

    return max(0, $stock);
}
```

**Beneficios**:
- Tiempo de consulta: 500ms → 5ms (100x más rápido)
- Escalabilidad: Funciona con millones de registros
- Mantiene trazabilidad completa en `inventario`

---

#### TODO 2: Agregar soft deletes
**Problema**: No hay forma de revertir eliminaciones accidentales.
**Solución**:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventario extends Model
{
    use SoftDeletes;

    protected $casts = [
        'deleted_at' => 'datetime',
        // ... otros casts
    ];
}
```

**Migración**:
```php
Schema::table('inventario', function (Blueprint $table) {
    $table->softDeletes();
});
```

**Uso**:
```php
// Eliminar (soft delete)
$movimiento->delete(); // deleted_at = now()

// Restaurar
$movimiento->restore();

// Ver eliminados
$eliminados = Inventario::onlyTrashed()->get();

// Eliminar permanentemente
$movimiento->forceDelete();
```

---

#### TODO 3: Agregar índices compuestos
**Problema**: Consultas lentas en reportes.
**Solución**:

```sql
-- Optimizar consulta de stock
CREATE INDEX idx_producto_tipo ON inventario(id_producto, tipo_movimiento);

-- Optimizar consultas de reportes por fecha
CREATE INDEX idx_producto_fecha ON inventario(id_producto, fecha_movimiento);

-- Optimizar búsqueda por referencia
CREATE INDEX idx_referencia_tipo ON inventario(referencia, tipo_movimiento);
```

**Beneficio**:
```php
// Antes: 2 consultas separadas (lento)
$entradas = Inventario::where('id_producto', 1)
    ->where('tipo_movimiento', 'entrada')
    ->sum('cantidad'); // 300ms

$salidas = Inventario::where('id_producto', 1)
    ->where('tipo_movimiento', 'salida')
    ->sum('cantidad'); // 300ms

// Después: 1 consulta optimizada (rápido)
$stock = DB::table('inventario')
    ->select(
        DB::raw('SUM(CASE WHEN tipo_movimiento = "entrada" THEN cantidad ELSE 0 END) as entradas'),
        DB::raw('SUM(CASE WHEN tipo_movimiento = "salida" THEN cantidad ELSE 0 END) as salidas')
    )
    ->where('id_producto', 1)
    ->first(); // 50ms
```

---

### Usuario.php

#### TODO 4: Implementar verificación de email
**Problema**: Usuarios pueden registrarse con emails falsos.
**Solución**: Laravel viene con esto built-in:

```php
// 1. Ya existe campo email_verified_at en tabla
// 2. Implementar interface en modelo (ya está: CanResetPassword)
// 3. Agregar middleware a rutas

// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// 4. Enviar email de verificación al crear usuario
$usuario = Usuario::create([...]);
$usuario->sendEmailVerificationNotification();
```

---

#### TODO 5: Agregar intentos de login fallidos
**Problema**: Sin protección contra ataques de fuerza bruta.
**Solución**:

```php
// Migración
Schema::table('usuarios', function (Blueprint $table) {
    $table->integer('intentos_fallidos')->default(0);
    $table->timestamp('bloqueado_hasta')->nullable();
});

// Modelo
public function incrementarIntentosFallidos(): void
{
    $this->intentos_fallidos++;

    if ($this->intentos_fallidos >= 5) {
        $this->bloqueado_hasta = now()->addMinutes(15);
    }

    $this->save();
}

public function resetearIntentosFallidos(): void
{
    $this->intentos_fallidos = 0;
    $this->bloqueado_hasta = null;
    $this->save();
}

public function estaBloqueado(): bool
{
    return $this->bloqueado_hasta && $this->bloqueado_hasta->isFuture();
}

// LoginController
public function login(Request $request)
{
    $usuario = Usuario::where('email', $request->email)->first();

    if (!$usuario) {
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    // Verificar bloqueo
    if ($usuario->estaBloqueado()) {
        $minutos = $usuario->bloqueado_hasta->diffInMinutes(now());
        return back()->withErrors([
            'email' => "Cuenta bloqueada. Intenta en {$minutos} minutos."
        ]);
    }

    // Verificar contraseña
    if (!Hash::check($request->password, $usuario->password)) {
        $usuario->incrementarIntentosFallidos();
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    // Login exitoso
    $usuario->resetearIntentosFallidos();
    $usuario->actualizarUltimoAcceso();
    auth()->login($usuario);

    return redirect()->route('dashboard');
}
```

---

#### TODO 6: Agregar log de actividades
**Problema**: No hay trazabilidad de qué hace cada usuario.
**Solución**: Usar paquete `spatie/laravel-activitylog`:

```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan migrate
```

```php
// Usuario.php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Usuario extends Authenticatable
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'email', 'estado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

// Uso automático
$usuario->nombre = 'Nuevo Nombre';
$usuario->save();
// Log: "Usuario cambió nombre de 'Juan' a 'Nuevo Nombre'"

// Log manual
activity()
    ->causedBy(auth()->user())
    ->performedOn($produccion)
    ->log('Registró producción de 500 botellones');

// Ver logs
$logs = Activity::forSubject($usuario)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

### Rol.php

#### TODO 7: Implementar permisos granulares
**Problema**: Solo hay 4 roles fijos, no hay permisos específicos.
**Solución**: Usar paquete `spatie/laravel-permission`:

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

```php
// Usuario.php
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasRoles;
}

// Crear roles y permisos
$rolAdmin = Role::create(['name' => 'admin']);
$rolProduccion = Role::create(['name' => 'produccion']);

$permisoCrearProduccion = Permission::create(['name' => 'crear produccion']);
$permisoVerProduccion = Permission::create(['name' => 'ver produccion']);
$permisoEditarProduccion = Permission::create(['name' => 'editar produccion']);
$permisoEliminarProduccion = Permission::create(['name' => 'eliminar produccion']);

// Asignar permisos a roles
$rolAdmin->givePermissionTo(Permission::all());
$rolProduccion->givePermissionTo([
    'crear produccion',
    'ver produccion',
    'editar produccion'
]);

// Uso en controladores
public function store(Request $request)
{
    if (!auth()->user()->can('crear produccion')) {
        abort(403);
    }
    // ...
}

// Uso en vistas
@can('crear produccion')
    <a href="{{ route('produccion.create') }}">Nueva Producción</a>
@endcan

// Middleware
Route::middleware(['permission:crear produccion'])->group(function () {
    Route::post('/produccion', [ProduccionController::class, 'store']);
});
```

---

## 🎯 CONCLUSIÓN

### Inventario.php - El Corazón del Sistema

**Responsabilidades**:
- Registrar TODOS los movimientos de stock (entradas/salidas)
- Calcular stock en tiempo real para validaciones
- Mantener trazabilidad completa con campo `referencia`
- Permitir consultas históricas y reportes

**Importancia crítica**:
- Alimentado automáticamente por `ProduccionDiariaController` y `SalidasController`
- Sin este modelo, no hay control de inventario
- Previene sobreventa con `stockDisponible()`

**Mejoras prioritarias**:
1. Tabla `stock_actual` con triggers (urgente para escalabilidad)
2. Índices compuestos (mejorar reportes)
3. Soft deletes (protección contra eliminaciones)

---

### Usuario.php - Seguridad y Autenticación

**Responsabilidades**:
- Autenticación de usuarios (login/logout)
- Gestión de contraseñas con hash bcrypt
- Control de accesos por roles
- Recuperación de contraseñas
- Auditoría de sesiones

**Importancia crítica**:
- Base del sistema de seguridad
- Previene accesos no autorizados
- Rastreo de quién hace qué en el sistema

**Mejoras prioritarias**:
1. Protección contra fuerza bruta (intentos fallidos)
2. Log de actividades (auditoría completa)
3. Verificación de email (seguridad)

---

### Rol.php - Control de Permisos

**Responsabilidades**:
- Definir niveles de acceso (admin, produccion, inventario, despacho)
- Agrupar usuarios por rol
- Simplificar verificación de permisos

**Importancia crítica**:
- Implementa principio de menor privilegio
- Separa responsabilidades por módulos
- Facilita gestión de permisos

**Mejoras prioritarias**:
1. Permisos granulares con spatie/laravel-permission
2. Roles personalizables por usuario
3. Matriz de permisos por módulo

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Sistema**: Agua Colegial v1.0
**Archivo**: 10-Modelos-Inventario-Usuario-Rol.md

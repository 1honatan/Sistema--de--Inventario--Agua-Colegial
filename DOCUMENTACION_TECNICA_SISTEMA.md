# DOCUMENTACION TECNICA - Sistema de Inventario Agua Colegial

## Arquitectura del Sistema

Este sistema esta desarrollado con **Laravel 11** siguiendo el patron **MVC (Modelo-Vista-Controlador)**.

```
agua_colegial/
├── app/
│   ├── Http/Controllers/    # Controladores (logica de negocio)
│   ├── Models/              # Modelos (representan tablas de BD)
│   └── Http/Middleware/     # Filtros de peticiones
├── resources/views/         # Vistas Blade (interfaz de usuario)
├── routes/web.php           # Definicion de rutas
├── database/migrations/     # Estructura de tablas
└── config/                  # Configuraciones del sistema
```

---

## MODULO 1: AUTENTICACION Y LOGIN

### Ubicacion del codigo:
- **Controlador:** `app/Http/Controllers/Auth/LoginController.php`
- **Vista:** `resources/views/auth/login.blade.php`
- **Modelo:** `app/Models/User.php`
- **Rutas:** `routes/web.php` (lineas de auth)

### Como funciona el Login:

#### 1. Mostrar formulario (metodo `showLoginForm`)
```php
public function showLoginForm(): View|RedirectResponse
{
    // Si ya esta autenticado, redirigir segun su rol
    if (Auth::check()) {
        return $this->redirigirSegunRol();
    }
    // Si no, mostrar el formulario de login
    return view('auth.login');
}
```
**Explicacion:** Primero verifica si el usuario ya tiene sesion activa con `Auth::check()`. Si ya esta logueado, lo redirige a su dashboard correspondiente. Si no, muestra la vista del formulario.

#### 2. Validacion de credenciales (metodo `login`)
```php
$credenciales = $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required', 'string', 'min:6'],
], [
    'email.required' => 'El correo electronico es obligatorio',
    'email.email' => 'El formato del correo no es valido',
    'password.required' => 'La contrasena es obligatoria',
    'password.min' => 'La contrasena debe tener al menos 6 caracteres',
]);
```
**Explicacion:** Laravel valida automaticamente los datos del formulario. Si no pasan la validacion, retorna errores al usuario.

#### 3. Proteccion contra ataques de fuerza bruta (Rate Limiting)
```php
$this->ensureIsNotRateLimited($request);
```
**Ubicacion:** Metodo `ensureIsNotRateLimited` (linea 83-99)

```php
protected function ensureIsNotRateLimited(Request $request): void
{
    if (!RateLimiter::tooManyAttempts($throttleKey, 5)) {
        return; // Permitir intento
    }
    // Si hay mas de 5 intentos fallidos, bloquear
    throw ValidationException::withMessages([
        'email' => trans('auth.throttle', ['seconds' => $seconds]),
    ]);
}
```
**Explicacion:** Si un usuario intenta loguearse mas de 5 veces con credenciales incorrectas, el sistema lo bloquea temporalmente. Esto previene ataques de fuerza bruta.

#### 4. Autenticacion con Laravel
```php
if (Auth::attempt($credenciales, $request->filled('remember'))) {
    $request->session()->regenerate(); // Regenerar sesion por seguridad
    // ... verificaciones adicionales
}
```
**Explicacion:** `Auth::attempt()` es el metodo de Laravel que:
1. Busca el usuario por email
2. Compara la contrasena hasheada
3. Crea la sesion si es correcto

#### 5. Verificacion de estado del usuario
```php
if ($usuario->estado !== 'activo') {
    Auth::logout();
    return back()->with('error', 'Su cuenta esta inactiva');
}
```
**Explicacion:** Aunque las credenciales sean correctas, si el usuario esta desactivado, no puede entrar.

#### 6. Redireccion segun rol
```php
protected function redirigirSegunRol(): RedirectResponse
{
    $rolNombre = $usuario->rol->nombre ?? 'admin';

    return match ($rolNombre) {
        'admin' => redirect()->route('admin.dashboard'),
        'produccion' => redirect()->route('control.produccion.index'),
        'inventario' => redirect()->route('inventario.index'),
        'despacho' => redirect()->route('control.salidas.index'),
        default => redirect()->route('login'),
    };
}
```
**Explicacion:** Dependiendo del rol del usuario, se redirige a diferentes dashboards. Usa `match` de PHP 8 (similar a switch pero mas limpio).

### Seguridad implementada:
1. **Hashing de contrasenas:** Laravel usa bcrypt automaticamente
2. **Rate Limiting:** Maximo 5 intentos por minuto
3. **Regeneracion de sesion:** Previene session fixation
4. **CSRF Protection:** Token en formularios (automatico en Laravel)
5. **Verificacion de estado:** Usuarios inactivos no pueden acceder

---

## MODULO 2: DASHBOARD

### Ubicacion del codigo:
- **Controlador:** `app/Http/Controllers/Admin/DashboardController.php`
- **Vista:** `resources/views/admin/dashboard.blade.php`
- **Ruta:** `Route::get('/admin/dashboard', [DashboardController::class, 'index'])`

### Como jala los datos el Dashboard:

#### Metodo `index()` - Obtener estadisticas
```php
public function index(): View
{
    // 1. Produccion del mes (desde tabla control_produccion_productos)
    $produccionMes = ProduccionProducto::whereHas('produccion', function($query) {
            $query->whereMonth('fecha', now()->month)
                  ->whereYear('fecha', now()->year);
        })
        ->sum('cantidad') ?? 0;

    // 2. Stock total del sistema
    $stockTotal = Inventario::where('tipo_movimiento', 'entrada')->sum('cantidad')
                - Inventario::where('tipo_movimiento', 'salida')->sum('cantidad');

    // 3. Personal activo
    $personalActivo = Personal::where('estado', 'activo')->count();

    // 4. Ultimos movimientos
    $ultimosMovimientos = Inventario::orderBy('created_at', 'desc')
        ->limit(8)
        ->get();

    // Enviar todas las variables a la vista
    return view('admin.dashboard', compact(
        'produccionMes',
        'stockTotal',
        'personalActivo',
        'ultimosMovimientos'
        // ... mas variables
    ));
}
```

**Explicacion detallada:**

1. **`whereHas()`**: Filtra registros que tienen una relacion. Busca productos de produccion donde la produccion sea del mes actual.

2. **`sum('cantidad')`**: Suma todos los valores de la columna cantidad.

3. **`count()`**: Cuenta cuantos registros hay.

4. **`compact()`**: Crea un array asociativo con las variables para enviar a la vista.

### En la vista (Blade):
```blade
<div class="card">
    <h3>Produccion del Mes</h3>
    <span>{{ number_format($produccionMes) }}</span>
</div>

<div class="card">
    <h3>Stock Total</h3>
    <span>{{ number_format($stockTotal) }}</span>
</div>
```
**Explicacion:** Las variables que enviamos con `compact()` se usan directamente en la vista con `{{ $variable }}`.

---

## MODULO 3: REGISTRO DE PERSONAL Y ASISTENCIA

### Ubicacion del codigo:
- **Controlador Personal:** `app/Http/Controllers/Control/EmpleadoController.php`
- **Controlador Asistencia:** `app/Http/Controllers/Control/AsistenciaSemanalController.php`
- **Modelo Personal:** `app/Models/Personal.php`
- **Modelo Asistencia:** `app/Models/AsistenciaSemanal.php`
- **Vista Cuaderno:** `resources/views/control/asistencia-semanal/index.blade.php`

### Como funciona la planilla tipo cuaderno:

#### 1. Obtener la semana
```php
public function index(Request $request)
{
    // Obtener fecha seleccionada o usar hoy
    $fechaSeleccionada = $request->get('semana')
        ? Carbon::parse($request->get('semana'))
        : Carbon::now();

    // Calcular inicio y fin de semana
    $inicioSemana = $fechaSeleccionada->copy()->startOfWeek(); // Lunes
    $finSemana = $fechaSeleccionada->copy()->endOfWeek();      // Domingo
```
**Explicacion:** `Carbon` es la libreria de Laravel para manejar fechas. `startOfWeek()` obtiene el lunes, `endOfWeek()` el domingo.

#### 2. Obtener asistencias agrupadas
```php
    $asistencias = AsistenciaSemanal::with('personal')
        ->whereBetween('fecha', [$inicioSemana, $finSemana])
        ->get()
        ->groupBy(function ($item) {
            return $item->personal_id . '_' . $item->fecha->format('Y-m-d');
        });
```
**Explicacion:**
- `with('personal')`: Carga la relacion (evita N+1 queries)
- `whereBetween()`: Filtra por rango de fechas
- `groupBy()`: Agrupa por empleado y fecha para mostrar en cuadricula

#### 3. Crear array de dias de la semana
```php
    $diasSemana = [];
    for ($i = 0; $i < 7; $i++) {
        $dia = $inicioSemana->copy()->addDays($i);
        $diasSemana[] = [
            'fecha' => $dia,
            'nombre' => AsistenciaSemanal::obtenerDiaSemana($dia),
            'numero' => $dia->day,
        ];
    }
```
**Explicacion:** Crea un array con los 7 dias de la semana para mostrar como columnas en la tabla.

### Donde se guardan los datos:
- **Tabla:** `asistencias_semanales`
- **Campos:** personal_id, fecha, dia_semana, entrada_hora, salida_hora, estado, observaciones

### Relacion Personal-Asistencia:
```php
// En modelo Personal.php
public function asistencias()
{
    return $this->hasMany(AsistenciaSemanal::class, 'personal_id');
}

// En modelo AsistenciaSemanal.php
public function personal()
{
    return $this->belongsTo(Personal::class, 'personal_id');
}
```
**Explicacion:** Un personal tiene MUCHAS asistencias (`hasMany`), y cada asistencia PERTENECE a un personal (`belongsTo`).

---

## MODULO 4: INVENTARIO Y PRODUCTOS

### Ubicacion del codigo:
- **Controlador:** `app/Http/Controllers/Inventario/InventarioController.php`
- **Modelo Inventario:** `app/Models/Inventario.php`
- **Modelo Producto:** `app/Models/Producto.php`
- **Vista:** `resources/views/inventario/index.blade.php`

### Como se relaciona Inventario con Productos:

#### Estructura de la tabla inventario:
```
inventario
├── id
├── id_producto (FK -> productos.id)
├── tipo_movimiento (entrada|salida)
├── cantidad
├── origen
├── destino
├── referencia
├── fecha_movimiento
└── observacion
```

#### Relacion en el modelo:
```php
// En Inventario.php
public function producto(): BelongsTo
{
    return $this->belongsTo(Producto::class, 'id_producto');
}
```
**Explicacion:** Cada movimiento de inventario esta relacionado con UN producto mediante `id_producto`.

### Calculo del stock disponible:
```php
public static function stockDisponible(int $idProducto): int
{
    // Sumar todas las entradas
    $entradas = self::where('id_producto', $idProducto)
        ->where('tipo_movimiento', 'entrada')
        ->sum('cantidad');

    // Sumar todas las salidas
    $salidas = self::where('id_producto', $idProducto)
        ->where('tipo_movimiento', 'salida')
        ->sum('cantidad');

    // Stock = Entradas - Salidas
    $stock = (int) ($entradas - $salidas);
    return max(0, $stock); // Nunca mostrar negativo
}
```
**Explicacion:** El stock no se guarda en un campo, se CALCULA sumando entradas menos salidas. Esto garantiza consistencia.

---

## MODULO 5: PRODUCCION DIARIA (Aumenta Inventario)

### Ubicacion del codigo:
- **Controlador:** `app/Http/Controllers/Control/ProduccionDiariaController.php`
- **Modelo:** `app/Models/Control/ProduccionDiaria.php`
- **Vista:** `resources/views/control/produccion/create.blade.php`

### Como la produccion aumenta el inventario:

#### Metodo store() - Guardar produccion
```php
public function store(Request $request)
{
    DB::beginTransaction(); // Iniciar transaccion

    try {
        // 1. Crear registro de produccion
        $produccion = ProduccionDiaria::create([
            'fecha' => $validated['fecha'],
            'responsable' => $validated['responsable'],
            // ...
        ]);

        // 2. Por cada producto producido
        foreach ($validated['productos'] as $productoData) {
            $producto = Producto::where('nombre', $productoData['producto'])->first();

            if ($producto) {
                // Guardar en tabla de produccion
                $produccion->productos()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                ]);

                // *** AQUI SE AUMENTA EL INVENTARIO ***
                Inventario::create([
                    'id_producto' => $producto->id,
                    'tipo_movimiento' => 'entrada',  // <-- ENTRADA
                    'cantidad' => $productoData['cantidad'],
                    'origen' => 'Produccion Diaria',
                    'referencia' => 'Produccion #' . $produccion->id,
                    'observacion' => 'Entrada automatica desde Control de Produccion',
                ]);
            }
        }

        DB::commit(); // Confirmar transaccion
    } catch (\Exception $e) {
        DB::rollBack(); // Revertir si hay error
    }
}
```

**Explicacion paso a paso:**
1. `DB::beginTransaction()`: Inicia una transaccion (todo o nada)
2. Crea el registro principal de produccion
3. Por cada producto, crea un registro en `control_produccion_productos`
4. **IMPORTANTE:** Tambien crea una ENTRADA en `inventario` con `tipo_movimiento = 'entrada'`
5. Si todo sale bien, `DB::commit()` guarda los cambios
6. Si hay error, `DB::rollBack()` revierte todo

---

## MODULO 6: SALIDA DE PRODUCTOS (Descuenta Inventario)

### Ubicacion del codigo:
- **Controlador:** `app/Http/Controllers/Control/SalidasController.php`
- **Modelo:** `app/Models/Control/SalidaProducto.php`
- **Vista:** `resources/views/control/salidas/create.blade.php`

### Como las salidas descuentan el inventario:

#### Metodo store() - Guardar salida
```php
public function store(Request $request)
{
    // 1. Validar stock disponible ANTES de guardar
    foreach ($productosEnviados as $productoId => $cantidad) {
        if ($cantidad > 0) {
            $stockDisponible = Inventario::stockDisponible($producto->id);

            if ($stockDisponible < $cantidad) {
                $erroresStock[] = "No hay suficiente stock de {$producto->nombre}";
            }
        }
    }

    // Si no hay stock suficiente, no permitir
    if (!empty($erroresStock)) {
        return redirect()->back()->with('error', implode('. ', $erroresStock));
    }

    DB::beginTransaction();

    try {
        // 2. Crear registro de salida
        $salida = SalidaProducto::create([...]);

        // 3. Por cada producto enviado
        foreach ($productosEnviados as $productoId => $cantidad) {
            if ($cantidad > 0) {
                // *** AQUI SE DESCUENTA EL INVENTARIO ***
                Inventario::create([
                    'id_producto' => $producto->id,
                    'tipo_movimiento' => 'salida',  // <-- SALIDA
                    'cantidad' => $cantidad,
                    'origen' => 'Almacen',
                    'destino' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                    'referencia' => 'Salida #' . $salida->id,
                ]);
            }
        }

        // 4. Registrar retornos como ENTRADA
        foreach ($retornosRecibidos as $productoId => $cantidad) {
            if ($cantidad > 0) {
                Inventario::create([
                    'id_producto' => $producto->id,
                    'tipo_movimiento' => 'entrada',  // <-- Los retornos son ENTRADA
                    'cantidad' => $cantidad,
                    'origen' => 'Distribuidor',
                    'destino' => 'Almacen',
                    'referencia' => 'Retorno - Salida #' . $salida->id,
                ]);
            }
        }

        DB::commit();
    }
}
```

**Explicacion:**
1. **Validacion de stock:** Antes de permitir la salida, verifica que haya suficiente
2. **Salida = tipo_movimiento 'salida':** Esto DESCUENTA del stock
3. **Retorno = tipo_movimiento 'entrada':** Los productos que regresan se SUMAN de nuevo

### Flujo completo del inventario:

```
PRODUCCION (+500 Agua Natural)
    ↓
    Inventario: entrada +500
    Stock: 500

SALIDA (-200 Agua Natural)
    ↓
    Inventario: salida -200
    Stock: 300

RETORNO (+50 Agua Natural)
    ↓
    Inventario: entrada +50
    Stock: 350
```

---

## RESUMEN DE RELACIONES ENTRE MODELOS

```
User
  └── belongsTo → Rol

Personal
  └── hasMany → AsistenciaSemanal

Producto
  └── hasMany → Inventario (movimientos)

ProduccionDiaria
  └── hasMany → ProduccionProducto
  └── hasMany → ProduccionMaterial

SalidaProducto
  └── (sin relaciones directas, usa campos de texto)

Inventario
  └── belongsTo → Producto
  └── belongsTo → Usuario
```

---

## TECNOLOGIAS UTILIZADAS

| Tecnologia | Version | Uso |
|------------|---------|-----|
| PHP | 8.2+ | Lenguaje backend |
| Laravel | 11.x | Framework MVC |
| MySQL | 8.0 | Base de datos |
| Blade | - | Motor de plantillas |
| Tailwind CSS | 3.x | Estilos CSS |
| Bootstrap | 5.x | Componentes UI |
| Carbon | - | Manejo de fechas |
| DOMPDF | - | Generacion de PDFs |

---

## PATRON MVC EN EL SISTEMA

### Modelo (Model)
- **Ubicacion:** `app/Models/`
- **Funcion:** Representa las tablas de la base de datos
- **Ejemplo:** `Inventario.php` representa la tabla `inventario`

### Vista (View)
- **Ubicacion:** `resources/views/`
- **Funcion:** Interfaz de usuario (HTML)
- **Ejemplo:** `dashboard.blade.php` muestra el panel principal

### Controlador (Controller)
- **Ubicacion:** `app/Http/Controllers/`
- **Funcion:** Logica de negocio, conecta Modelo con Vista
- **Ejemplo:** `DashboardController.php` obtiene datos y los envia a la vista

### Flujo de una peticion:
```
Usuario → Ruta → Middleware → Controlador → Modelo → BD
                                    ↓
Usuario ← Vista ← Controlador ←────┘
```

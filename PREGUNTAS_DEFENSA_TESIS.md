# 200 PREGUNTAS DE DEFENSA DE TESIS
## Sistema de Inventario Agua Colegial

---

## SECCION 1: PREGUNTAS GENERALES DEL PROYECTO (1-25)

1. **¿Cual es el objetivo principal de tu sistema?**
   R: Gestionar el inventario, produccion, despacho y control de personal de una empresa de agua purificada, automatizando procesos que antes se hacian manualmente.

2. **¿Por que elegiste Laravel como framework?**
   R: Porque Laravel es un framework PHP robusto con arquitectura MVC, ORM Eloquent para base de datos, sistema de autenticacion integrado, y gran comunidad de soporte.

3. **¿Que problema resuelve tu sistema?**
   R: Resuelve el problema del control manual de inventario, evitando errores de conteo, perdida de informacion, y falta de trazabilidad en los movimientos de productos.

4. **¿Quienes son los usuarios del sistema?**
   R: Administradores, personal de produccion, personal de inventario, y choferes de despacho. Cada uno tiene un rol con permisos especificos.

5. **¿Que metodologia de desarrollo usaste?**
   R: Metodologia agil con iteraciones incrementales, desarrollando modulo por modulo y probando cada funcionalidad.

6. **¿Cuales son los modulos principales del sistema?**
   R: Login/Autenticacion, Dashboard, Inventario, Produccion Diaria, Salidas/Despacho, Personal, Asistencia, Mantenimiento, Vehiculos y Reportes.

7. **¿Como garantizas la integridad de los datos?**
   R: Usando transacciones de base de datos (DB::beginTransaction), validaciones en el servidor, y restricciones de llaves foraneas.

8. **¿Que version de PHP usaste y por que?**
   R: PHP 8.2 porque Laravel 11 requiere minimo PHP 8.1, y la version 8.2 tiene mejoras de rendimiento y nuevas caracteristicas como tipos de retorno.

9. **¿Como organizaste tu base de datos?**
   R: Usando migraciones de Laravel que permiten versionar la estructura de la base de datos y facilitan el despliegue.

10. **¿Que es una migracion en Laravel?**
    R: Es un archivo PHP que define la estructura de una tabla (crear, modificar, eliminar columnas) y permite controlar versiones de la BD.

11. **¿Como manejas los errores en tu sistema?**
    R: Usando try-catch en operaciones criticas, validaciones de Laravel, y mensajes flash para informar al usuario.

12. **¿Que base de datos usaste?**
    R: MySQL 8.0, porque es estable, tiene buen rendimiento, y Laravel tiene excelente soporte para ella.

13. **¿Como probaste tu sistema?**
    R: Pruebas manuales de cada modulo, verificando flujos completos, validaciones, y casos de error.

14. **¿Cuantas tablas tiene tu base de datos?**
    R: Aproximadamente 15 tablas principales: users, roles, personal, productos, inventario, control_produccion_diaria, control_salidas_productos, vehiculos, asistencias_semanales, etc.

15. **¿Que es el patron MVC?**
    R: Modelo-Vista-Controlador. Separa la logica de datos (Modelo), la interfaz (Vista), y la logica de negocio (Controlador).

16. **¿Donde se encuentra cada parte del MVC en tu proyecto?**
    R: Modelos en app/Models, Vistas en resources/views, Controladores en app/Http/Controllers.

17. **¿Por que usaste Blade para las vistas?**
    R: Blade es el motor de plantillas de Laravel, permite herencia de layouts, componentes reutilizables, y sintaxis limpia.

18. **¿Como gestionas las sesiones?**
    R: Laravel maneja las sesiones automaticamente con su sistema de autenticacion y el middleware de sesion.

19. **¿Que es Eloquent ORM?**
    R: Es el ORM de Laravel que permite interactuar con la base de datos usando objetos PHP en lugar de SQL directo.

20. **¿Que ventajas tiene usar un ORM?**
    R: Codigo mas limpio, proteccion contra SQL injection, relaciones faciles de definir, y portabilidad entre bases de datos.

21. **¿Como defines una relacion en Eloquent?**
    R: Usando metodos como hasMany(), belongsTo(), hasOne() en los modelos para definir como se relacionan las tablas.

22. **¿Que es un middleware?**
    R: Es un filtro que se ejecuta antes o despues de una peticion HTTP. Por ejemplo, verificar si el usuario esta autenticado.

23. **¿Que middlewares usas en tu sistema?**
    R: auth (verificar autenticacion), guest (solo no autenticados), y SecurityHeaders (cabeceras de seguridad).

24. **¿Como proteges las rutas de tu sistema?**
    R: Usando el middleware 'auth' que verifica que el usuario tenga sesion activa antes de acceder.

25. **¿Que es composer y para que lo usas?**
    R: Es el gestor de dependencias de PHP. Lo uso para instalar Laravel y sus paquetes como DomPDF para reportes.

---

## SECCION 2: MODULO DE AUTENTICACION (26-50)

26. **¿Como funciona el login de tu sistema?**
    R: El usuario ingresa email y password, Laravel valida los datos, verifica en la BD con password hasheado, y crea una sesion.

27. **¿Donde esta el codigo del login?**
    R: En app/Http/Controllers/Auth/LoginController.php

28. **¿Como se validan las credenciales?**
    R: Usando el metodo validate() de Laravel que verifica que el email tenga formato correcto y la password minimo 6 caracteres.

29. **¿Que metodo de Laravel verifica las credenciales?**
    R: Auth::attempt() que busca el usuario por email y compara el password hasheado.

30. **¿Como se almacenan las contrasenas?**
    R: Hasheadas con bcrypt. Laravel nunca guarda passwords en texto plano.

31. **¿Que es bcrypt?**
    R: Es un algoritmo de hash seguro que genera un hash unico para cada password, incluso si son iguales.

32. **¿Como proteges contra ataques de fuerza bruta?**
    R: Con Rate Limiting. Si hay mas de 5 intentos fallidos, se bloquea temporalmente al usuario.

33. **¿Donde implementaste el Rate Limiting?**
    R: En el metodo ensureIsNotRateLimited() del LoginController, usando RateLimiter de Laravel.

34. **¿Que pasa si un usuario intenta loguearse muchas veces?**
    R: Despues de 5 intentos fallidos, se bloquea por unos segundos y muestra mensaje de "demasiados intentos".

35. **¿Como regeneras la sesion despues del login?**
    R: Con $request->session()->regenerate() que crea un nuevo ID de sesion para prevenir session fixation.

36. **¿Que es session fixation?**
    R: Un ataque donde el atacante fija el ID de sesion antes del login. Regenerar la sesion lo previene.

37. **¿Como verificas si un usuario esta activo?**
    R: Despues de autenticar, verifico que $usuario->estado === 'activo'. Si no, hago logout.

38. **¿Que pasa si un usuario inactivo intenta entrar?**
    R: El sistema lo autentica pero inmediatamente hace logout y muestra "Su cuenta esta inactiva".

39. **¿Como rediriges segun el rol del usuario?**
    R: Con el metodo redirigirSegunRol() que usa match() de PHP 8 para enviar a diferentes dashboards.

40. **¿Que roles tiene tu sistema?**
    R: admin, produccion, inventario, despacho. Cada uno ve diferentes modulos.

41. **¿Como se relaciona User con Rol?**
    R: User tiene un campo id_rol que es llave foranea a la tabla roles. Relacion belongsTo.

42. **¿Como obtienes el rol del usuario?**
    R: Con $usuario->rol->nombre usando la relacion definida en el modelo User.

43. **¿Que es CSRF y como lo proteges?**
    R: Cross-Site Request Forgery. Laravel genera un token unico por sesion que se incluye en cada formulario.

44. **¿Como se incluye el token CSRF en formularios?**
    R: Con la directiva @csrf en Blade que genera un input hidden con el token.

45. **¿Como funciona el logout?**
    R: Auth::logout() cierra la sesion, session()->invalidate() destruye la sesion, regenerateToken() crea nuevo CSRF.

46. **¿Por que regeneras el token CSRF al cerrar sesion?**
    R: Para invalidar cualquier formulario que pudiera estar abierto y prevenir ataques CSRF residuales.

47. **¿Que es el metodo throttleKey()?**
    R: Genera una clave unica combinando email e IP para identificar intentos de login del mismo origen.

48. **¿Por que usas IP en el throttle key?**
    R: Para que el rate limiting sea por combinacion de email+IP, no solo email. Asi un atacante no bloquea usuarios legitimos.

49. **¿Que es Auth::check()?**
    R: Metodo que retorna true si hay un usuario autenticado en la sesion actual.

50. **¿Como verificas si el usuario ya esta logueado al mostrar el formulario?**
    R: En showLoginForm() primero verifico Auth::check(), si es true redirijo al dashboard en vez de mostrar el login.

---

## SECCION 3: DASHBOARD Y ESTADISTICAS (51-75)

51. **¿Que muestra el dashboard?**
    R: KPIs principales: produccion del mes, stock total, personal activo, ultimos movimientos, salidas recientes.

52. **¿Donde esta el controlador del dashboard?**
    R: app/Http/Controllers/Admin/DashboardController.php

53. **¿Como calculas la produccion del mes?**
    R: Sumando cantidad de ProduccionProducto donde la produccion tenga fecha del mes actual.

54. **¿Que es whereHas()?**
    R: Filtra registros basandose en condiciones de una relacion. Ej: productos donde SU produccion sea de este mes.

55. **¿Como calculas el stock total?**
    R: Sumando todas las entradas de inventario menos todas las salidas.

56. **¿Por que no guardas el stock en un campo?**
    R: Porque calcularlo garantiza consistencia. Un campo podria desincronizarse con los movimientos reales.

57. **¿Que ventaja tiene calcular el stock?**
    R: Siempre es exacto, hay trazabilidad de cada movimiento, y se puede auditar facilmente.

58. **¿Como obtienes los ultimos movimientos?**
    R: Con Inventario::orderBy('created_at', 'desc')->limit(8)->get()

59. **¿Que hace orderBy('desc')?**
    R: Ordena de mayor a menor (descendente), asi los mas recientes aparecen primero.

60. **¿Que hace limit()?**
    R: Limita la cantidad de registros retornados. limit(8) trae solo los 8 mas recientes.

61. **¿Como envias datos a la vista?**
    R: Con compact() que crea un array asociativo: compact('variable1', 'variable2').

62. **¿Que es compact()?**
    R: Funcion PHP que toma nombres de variables y crea array ['nombre' => $valor].

63. **¿Como accedes a esas variables en Blade?**
    R: Directamente con {{ $nombreVariable }} o @foreach($array as $item).

64. **¿Que significa {{ }} en Blade?**
    R: Imprime el valor escapando HTML para prevenir XSS. Es echo con htmlspecialchars().

65. **¿Como mostrar HTML sin escapar?**
    R: Con {!! $variable !!} pero solo para contenido confiable, nunca datos de usuario.

66. **¿Como actualizas el dashboard en tiempo real?**
    R: Con el metodo getData() que retorna JSON para llamadas AJAX periodicas.

67. **¿Que es JsonResponse?**
    R: Un tipo de respuesta que retorna datos en formato JSON, usado para APIs y AJAX.

68. **¿Como generas una respuesta JSON?**
    R: Con return response()->json(['clave' => $valor]);

69. **¿Que estadisticas de modulos muestras?**
    R: Total de salidas, produccion, mantenimientos, fumigaciones, fosa septica, tanques, insumos, asistencias.

70. **¿Como cuentas registros de una tabla?**
    R: Con el metodo count(). Ej: SalidaProducto::count()

71. **¿Que es Carbon?**
    R: Libreria de Laravel para manipular fechas. Extiende DateTime de PHP con metodos utiles.

72. **¿Como obtienes el mes actual con Carbon?**
    R: Con now()->month o Carbon::now()->month

73. **¿Que hace whereMonth()?**
    R: Filtra por el mes de una fecha. whereMonth('fecha', 12) trae registros de diciembre.

74. **¿Como filtras por mes Y ano?**
    R: Combinando whereMonth() y whereYear() para evitar traer el mismo mes de otro ano.

75. **¿Que es number_format()?**
    R: Funcion PHP que formatea numeros con separadores de miles. number_format(1000) = "1,000"

---

## SECCION 4: INVENTARIO Y PRODUCTOS (76-100)

76. **¿Como se estructura la tabla inventario?**
    R: id, id_producto, tipo_movimiento (entrada/salida), cantidad, origen, destino, referencia, fecha_movimiento, observacion.

77. **¿Que es tipo_movimiento?**
    R: Campo que indica si es 'entrada' (aumenta stock) o 'salida' (disminuye stock).

78. **¿Como se relaciona inventario con productos?**
    R: Con llave foranea id_producto que referencia a productos.id. Relacion belongsTo.

79. **¿Donde defines la relacion?**
    R: En el modelo Inventario.php con el metodo producto() que retorna belongsTo(Producto::class).

80. **¿Que es belongsTo?**
    R: Relacion donde el modelo actual pertenece a otro. Inventario pertenece a UN producto.

81. **¿Como accedes al nombre del producto desde inventario?**
    R: Con $movimiento->producto->nombre usando la relacion.

82. **¿Que es el metodo stockDisponible()?**
    R: Metodo estatico que calcula el stock actual de un producto sumando entradas menos salidas.

83. **¿Por que es static?**
    R: Para llamarlo sin instanciar: Inventario::stockDisponible(1) en vez de crear objeto.

84. **¿Como sumas solo entradas?**
    R: Filtrando: self::where('tipo_movimiento', 'entrada')->sum('cantidad')

85. **¿Que hace sum()?**
    R: Suma los valores de una columna. sum('cantidad') suma todas las cantidades.

86. **¿Por que usas max(0, $stock)?**
    R: Para nunca mostrar stock negativo. Si hay inconsistencia, muestra 0 en vez de -50.

87. **¿Que son los scopes en Eloquent?**
    R: Metodos que encapsulan consultas comunes. scopeEntradas() filtra solo entradas.

88. **¿Como defines un scope?**
    R: Con metodo scopeNombre($query) { return $query->where(...) }

89. **¿Como usas un scope?**
    R: Sin el prefijo scope: Inventario::entradas()->get()

90. **¿Que hace registrarEntrada()?**
    R: Metodo helper que crea un movimiento de tipo entrada con los datos proporcionados.

91. **¿Que hace registrarSalida()?
    R: Similar pero con tipo_movimiento = 'salida'.

92. **¿Que es $fillable en el modelo?**
    R: Array que define que campos se pueden asignar masivamente con create() o update().

93. **¿Por que es importante $fillable?**
    R: Protege contra mass assignment vulnerability. Solo campos listados se pueden asignar.

94. **¿Que son los casts?**
    R: Conversiones automaticas de tipos. 'fecha' => 'datetime' convierte string a objeto Carbon.

95. **¿Como defines casts?**
    R: Con la propiedad $casts en el modelo: protected $casts = ['cantidad' => 'integer']

96. **¿Como muestras el historial de movimientos?**
    R: Ordenando por fecha descendente y mostrando tipo, cantidad, origen, destino.

97. **¿Como filtras movimientos por fecha?**
    R: Con whereBetween('fecha_movimiento', [$inicio, $fin])

98. **¿Que hace with() en consultas?**
    R: Eager loading - carga relaciones en una sola consulta para evitar N+1 queries.

99. **¿Que es el problema N+1?**
    R: Cuando haces 1 query para obtener N registros, y luego N queries mas para obtener relaciones.

100. **¿Como lo solucionas?**
     R: Con with(): Inventario::with('producto')->get() hace solo 2 queries totales.

---

## SECCION 5: PRODUCCION DIARIA (101-125)

101. **¿Que registra el modulo de produccion?**
     R: Fecha, responsable, productos producidos con cantidades, materiales usados, observaciones.

102. **¿Donde esta el controlador?**
     R: app/Http/Controllers/Control/ProduccionDiariaController.php

103. **¿Que tablas usa este modulo?**
     R: control_produccion_diaria (registro principal), control_produccion_productos (detalle), control_produccion_materiales.

104. **¿Que relacion hay entre produccion y productos?**
     R: Una produccion hasMany ProduccionProducto. Una produccion tiene muchos productos.

105. **¿Como guardas los productos producidos?**
     R: Con $produccion->productos()->create([...]) usando la relacion hasMany.

106. **¿Que es una transaccion de base de datos?**
     R: Operacion atomica donde todas las queries se ejecutan o ninguna. Todo o nada.

107. **¿Como inicias una transaccion?**
     R: Con DB::beginTransaction()

108. **¿Como confirmas una transaccion?**
     R: Con DB::commit() que guarda todos los cambios.

109. **¿Como reviertes una transaccion?**
     R: Con DB::rollBack() que deshace todos los cambios.

110. **¿Por que usas transacciones en produccion?**
     R: Porque se guardan multiples registros (produccion + productos + inventario). Si uno falla, todo se revierte.

111. **¿Como se aumenta el inventario al producir?**
     R: Creando un registro en Inventario con tipo_movimiento = 'entrada' por cada producto.

112. **¿Que referencia se guarda en inventario?**
     R: 'Produccion #' . $produccion->id para trazabilidad.

113. **¿Como evitas duplicados de produccion?**
     R: Verificando si ya existe registro con misma fecha y responsable antes de crear.

114. **¿Que hace exists()?**
     R: Retorna true/false si hay al menos un registro que cumpla la condicion.

115. **¿Como buscas un producto por nombre?**
     R: Con Producto::where('nombre', $nombre)->first()

116. **¿Que retorna first()?**
     R: El primer registro que cumpla la condicion, o null si no hay.

117. **¿Como manejas el error si el producto no existe?**
     R: Verifico if ($producto) antes de usarlo. Si es null, no hago nada con ese item.

118. **¿Que pasa al editar una produccion?**
     R: Se eliminan los registros de inventario anteriores y se crean nuevos con los datos actualizados.

119. **¿Por que eliminas y recreas en vez de actualizar?**
     R: Porque pueden cambiar los productos y cantidades. Es mas seguro recrear todo.

120. **¿Como eliminas los movimientos de inventario relacionados?**
     R: Con Inventario::where('referencia', 'Produccion #' . $id)->delete()

121. **¿Que pasa al eliminar una produccion?**
     R: Se eliminan los productos relacionados, materiales, y movimientos de inventario.

122. **¿Como eliminas registros relacionados?**
     R: Con $produccion->productos()->delete() antes de eliminar el principal.

123. **¿Que personal puede registrar produccion?**
     R: Solo personal de produccion e inventario. Se excluyen choferes y distribuidores.

124. **¿Como filtras el personal?**
     R: Con whereNotIn('cargo', ['Chofer (Despacho)', 'Ayudante (Distribuidor)'])

125. **¿Que es whereNotIn?**
     R: Filtra registros donde el campo NO esta en el array dado.

---

## SECCION 6: SALIDAS Y DESPACHO (126-150)

126. **¿Que tipos de salida tiene el sistema?**
     R: Despacho Interno, Pedido Cliente, Venta Directa.

127. **¿Que informacion registra una salida?**
     R: Tipo, fecha, chofer, distribuidor, vehiculo, productos enviados, retornos, observaciones.

128. **¿Como se descuenta del inventario?**
     R: Creando registros con tipo_movimiento = 'salida' por cada producto.

129. **¿Que son los retornos?**
     R: Productos que el chofer no vendio y regresa. Se registran como 'entrada' en inventario.

130. **¿Por que los retornos son entrada?**
     R: Porque regresan al almacen, aumentando el stock disponible.

131. **¿Como validas que hay stock suficiente?**
     R: Antes de guardar, verifico Inventario::stockDisponible() >= cantidad solicitada.

132. **¿Que pasa si no hay stock?**
     R: Se muestra error y no se permite la salida. El usuario debe ajustar cantidades.

133. **¿Como manejas multiples productos en una salida?**
     R: Los campos se guardan directamente: agua_natural, agua_limon, botellones, etc.

134. **¿Por que no usas tabla de detalle para salidas?**
     R: Por simplicidad. Los productos son fijos y se guardan como columnas en la tabla principal.

135. **¿Como calculas el total de retornos?**
     R: Sumando todos los campos de retorno individuales.

136. **¿Que es el campo choreados?**
     R: Productos danados o que se derramaron. Se registran para control de perdidas.

137. **¿Como se muestra la semana actual en salidas?**
     R: Calculando inicio y fin de semana con Carbon y filtrando por ese rango.

138. **¿Como navegas entre semanas?**
     R: Con parametro 'semana' que suma o resta al offset actual.

139. **¿Como mantienes el filtro al cambiar semana?**
     R: Incluyendo el parametro tipo_salida en los enlaces de navegacion.

140. **¿Que vehiculos se muestran?**
     R: Solo vehiculos activos, ordenados por placa.

141. **¿Como relacionas chofer con vehiculo?**
     R: El vehiculo tiene campo 'responsable' con el nombre del chofer asignado.

142. **¿Que validaciones tiene el formulario de salida?**
     R: Tipo requerido, fecha requerida (segun tipo), productos array, cantidades minimo 0.

143. **¿Que pasa al editar una salida?**
     R: Se eliminan movimientos de inventario anteriores y se recrean con nuevos datos.

144. **¿Como muestras la hora de llegada?**
     R: Campo opcional que registra cuando regreso el chofer.

145. **¿Que informacion va en observaciones?**
     R: Notas sobre botellones rotos, productos choreados, incidentes, etc.

146. **¿Como generas el PDF de salida?**
     R: Usando DomPDF con una vista Blade especial para impresion.

147. **¿Que es DomPDF?**
     R: Libreria PHP que convierte HTML a PDF. Instalada via Composer.

148. **¿Como filtras por tipo de salida?**
     R: Con select que envia parametro y where en el controlador.

149. **¿Como muestras solo pedidos de un cliente?**
     R: Filtrando por nombre_cliente en la consulta.

150. **¿Que campos son especificos de Pedido Cliente?**
     R: nombre_cliente, direccion_entrega, telefono_cliente.

---

## SECCION 7: PERSONAL Y ASISTENCIA (151-175)

151. **¿Que datos guarda la tabla personal?**
     R: nombre_completo, cedula, email, telefono, cargo, area, fecha_ingreso, estado, foto.

152. **¿Que cargos existen?**
     R: Chofer (Despacho), Ayudante (Distribuidor), Supervisor, Operador de Produccion, etc.

153. **¿Como funciona la asistencia semanal?**
     R: Muestra cuadricula de 7 dias con personal en filas y dias en columnas.

154. **¿Donde se guarda la asistencia?**
     R: En tabla asistencias_semanales con personal_id, fecha, entrada_hora, salida_hora, estado.

155. **¿Que estados de asistencia hay?**
     R: presente, ausente, permiso, tardanza.

156. **¿Como obtienes el nombre del dia?**
     R: Con metodo obtenerDiaSemana() que usa Carbon para obtener nombre en espanol.

157. **¿Como agrupas asistencias por empleado y dia?**
     R: Con groupBy() usando clave compuesta: personal_id + fecha.

158. **¿Que es el registro rapido?**
     R: Interfaz simplificada para marcar entrada/salida sin llenar formulario completo.

159. **¿Como verificas si ya tiene entrada?**
     R: Buscando registro del dia con entrada_hora NOT NULL y salida_hora NULL.

160. **¿Como registras la salida?**
     R: Actualizando el registro existente agregando salida_hora.

161. **¿Que es mi-registro?**
     R: Vista donde el propio empleado marca su entrada/salida sin ser admin.

162. **¿Como sabes que empleado es el usuario?**
     R: El modelo User tiene campo personal_id que relaciona con Personal.

163. **¿Que validaciones tiene la asistencia?**
     R: personal_id existe, fecha valida, formato hora HH:MM, salida despues de entrada.

164. **¿Como navegas entre semanas en asistencia?**
     R: Con parametro 'semana' que recibe fecha y calcula inicio/fin de esa semana.

165. **¿Como muestras el cuaderno de asistencia?**
     R: Con tabla HTML donde filas son empleados y columnas son dias de la semana.

166. **¿Como obtienes el personal activo?**
     R: Personal::where('estado', 'activo')->orderBy('nombre_completo')->get()

167. **¿Que relacion tiene Personal con Asistencia?**
     R: Personal hasMany AsistenciaSemanal. Un empleado tiene muchas asistencias.

168. **¿Como cargas asistencias con personal?**
     R: AsistenciaSemanal::with('personal')->get() para eager loading.

169. **¿Que hace startOfWeek()?**
     R: Retorna el lunes de la semana de la fecha dada.

170. **¿Que hace endOfWeek()?**
     R: Retorna el domingo de la semana de la fecha dada.

171. **¿Como creas array de dias de la semana?**
     R: Loop de 0 a 6, sumando dias a inicioSemana con addDays($i).

172. **¿Por que usas copy() con Carbon?**
     R: Porque Carbon es mutable. copy() crea clon para no modificar original.

173. **¿Como eliminas una asistencia?**
     R: Buscando por ID con findOrFail() y llamando delete().

174. **¿Que hace findOrFail()?**
     R: Busca por ID y lanza excepcion 404 si no existe.

175. **¿Como guardas quien registro la asistencia?**
     R: Campo registrado_por con el personal_id del usuario autenticado.

---

## SECCION 8: VISTAS Y BLADE (176-190)

176. **¿Que es Blade?**
     R: Motor de plantillas de Laravel que permite mezclar PHP con HTML de forma limpia.

177. **¿Que es @extends?**
     R: Directiva que indica que la vista hereda de un layout base.

178. **¿Que es @section?**
     R: Define contenido que se insertara en un @yield del layout padre.

179. **¿Que es @yield?**
     R: Marcador en el layout donde se insertara el contenido de @section.

180. **¿Como iteras en Blade?**
     R: Con @foreach($items as $item) ... @endforeach

181. **¿Como haces condicionales?**
     R: Con @if($condicion) ... @elseif ... @else ... @endif

182. **¿Que es @csrf?**
     R: Genera input hidden con token CSRF para proteger formularios.

183. **¿Que es @method('PUT')?**
     R: Genera input para simular metodos HTTP que HTML no soporta (PUT, DELETE).

184. **¿Como muestras errores de validacion?**
     R: Con @error('campo') {{ $message }} @enderror

185. **¿Que hace old()?**
     R: Recupera el valor anterior del input si hubo error de validacion.

186. **¿Como incluyes CSS y JS?**
     R: Con @push('styles') y @push('scripts') en secciones @stack del layout.

187. **¿Que es @include?**
     R: Incluye otra vista parcial dentro de la actual.

188. **¿Que es @component?**
     R: Incluye un componente Blade reutilizable con slots para contenido.

189. **¿Como formateas fechas en Blade?**
     R: Con Carbon: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}

190. **¿Como formateas numeros?**
     R: Con number_format(): {{ number_format($cantidad) }}

---

## SECCION 9: SEGURIDAD Y BUENAS PRACTICAS (191-200)

191. **¿Como proteges contra SQL Injection?**
     R: Eloquent usa prepared statements automaticamente. Nunca concateno SQL.

192. **¿Como proteges contra XSS?**
     R: Blade escapa automaticamente con {{ }}. Solo uso {!! !!} para HTML confiable.

193. **¿Como proteges contra CSRF?**
     R: Con token @csrf en formularios que Laravel valida automaticamente.

194. **¿Que headers de seguridad implementas?**
     R: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection via middleware.

195. **¿Como manejas archivos subidos?**
     R: Validando tipo y tamano, guardando en storage con nombre unico.

196. **¿Por que usas validacion en servidor?**
     R: Porque validacion JavaScript se puede saltar. El servidor es la ultima linea de defensa.

197. **¿Como proteges rutas por rol?**
     R: Con middleware que verifica el rol del usuario antes de permitir acceso.

198. **¿Que es el principio de minimo privilegio?**
     R: Dar solo los permisos necesarios. Produccion no ve inventario, despacho no ve produccion.

199. **¿Como manejas errores en produccion?**
     R: Logs en storage/logs, mensajes genericos al usuario, notificacion a admin.

200. **¿Que harias para mejorar el sistema?**
     R: Agregar tests automatizados, cache para consultas frecuentes, API REST para app movil, backups automaticos.

---

## CONSEJOS PARA LA DEFENSA

1. **Se honesto:** Si no sabes algo, di "No recuerdo exactamente pero puedo explicar el concepto general"

2. **Explica con ejemplos:** "Por ejemplo, cuando un usuario hace login, primero se valida..."

3. **Usa terminos tecnicos:** Demuestra que entiendes: "Uso transacciones para garantizar atomicidad"

4. **Relaciona con el proyecto:** "En mi sistema esto se usa en el modulo de inventario cuando..."

5. **Muestra el codigo:** Si te piden, abre el archivo y senala la linea exacta

6. **Conoce tus numeros:** Cuantas tablas, cuantos modulos, que version de PHP/Laravel

7. **Prepara demos:** Ten el sistema corriendo para mostrar funcionalidades

8. **Anticipa preguntas:** Lee esta lista varias veces antes de la defensa

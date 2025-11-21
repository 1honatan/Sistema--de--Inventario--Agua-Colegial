# Estructura de Base de Datos

## Tablas Principales

### Usuarios y Autenticación
| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema |
| `roles` | Roles de usuario |
| `permissions` | Permisos del sistema |
| `model_has_roles` | Relación usuario-rol |
| `role_has_permissions` | Relación rol-permiso |

### Personal
| Tabla | Descripción |
|-------|-------------|
| `personal` | Empleados de la empresa |

### Inventario
| Tabla | Descripción |
|-------|-------------|
| `productos` | Catálogo de productos |
| `inventarios` | Movimientos de inventario |
| `v_stock_actual` | Vista de stock actual |

### Producción
| Tabla | Descripción |
|-------|-------------|
| `control_produccion_diaria` | Producción diaria |
| `control_produccion_productos` | Productos por producción |
| `control_produccion_materiales` | Materiales usados |

### Salidas
| Tabla | Descripción |
|-------|-------------|
| `control_salidas_productos` | Salidas/despachos diarios |

### Controles
| Tabla | Descripción |
|-------|-------------|
| `control_insumos` | Recepción de insumos |
| `control_mantenimiento_equipos` | Mantenimientos |
| `control_tanques_agua` | Limpieza de tanques |
| `control_fosa_septica` | Limpieza fosa séptica |
| `control_fumigacion` | Fumigaciones |

### Gestión
| Tabla | Descripción |
|-------|-------------|
| `vehiculos` | Flota de vehículos |
| `historial_reportes` | Reportes generados |

## Campos Comunes

### Timestamps
- `created_at` - Fecha de creación
- `updated_at` - Fecha de actualización

### Estados
- `estado` - activo/inactivo

## Relaciones Principales

```
users ─────────┬──────── roles
               │
personal ──────┼──────── vehiculos
               │
productos ─────┼──────── inventarios
               │
control_produccion_diaria ──┬── control_produccion_productos
                            └── control_produccion_materiales
```

## Vista de Stock Actual
```sql
CREATE VIEW v_stock_actual AS
SELECT
    p.id,
    p.nombre,
    p.tipo,
    p.unidad_medida,
    p.estado,
    COALESCE(SUM(CASE
        WHEN i.tipo_movimiento = 'entrada' THEN i.cantidad
        WHEN i.tipo_movimiento = 'salida' THEN -i.cantidad
        ELSE 0
    END), 0) as stock_actual
FROM productos p
LEFT JOIN inventarios i ON p.id = i.id_producto
GROUP BY p.id, p.nombre, p.tipo, p.unidad_medida, p.estado;
```

## Índices Importantes
- `inventarios.id_producto` - Búsqueda por producto
- `control_salidas_productos.fecha` - Búsqueda por fecha
- `personal.estado` - Filtro por estado

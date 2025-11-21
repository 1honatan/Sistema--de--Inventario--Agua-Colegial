# 🌊 Sistema de Gestión Agua Colegial

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8.2.12-blue)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-orange)
![License](https://img.shields.io/badge/License-Proprietary-green)

## 📋 Descripción

Sistema integral de gestión empresarial para **Agua Colegial**, que automatiza y controla todo el proceso productivo y distributivo de agua embotellada y productos relacionados.

## ✨ Características Principales

- ✅ **Gestión Automática de Inventario** - Se actualiza solo con producción y salidas
- ✅ **Alertas Inteligentes de Stock** - Umbrales personalizados por producto
- ✅ **Control de Producción Diaria** - Registro detallado con trazabilidad
- ✅ **Sistema de Distribución** - Validación de stock y asignación de vehículos
- ✅ **Gestión de Personal** - Control de empleados y responsables
- ✅ **Multi-Rol** - Admin, Producción, Inventario
- ✅ **Reportes y Exportaciones** - PDF y Excel
- ✅ **Interfaz Moderna** - Diseño responsive institucional

## 🚀 Inicio Rápido

```bash
# 1. Clonar repositorio
git clone [URL_DEL_REPO]
cd agua_colegial

# 2. Instalar dependencias
composer install

# 3. Configurar environment
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env
DB_DATABASE=agua_colegial_bd
DB_USERNAME=root
DB_PASSWORD=

# 5. Ejecutar migraciones
php artisan migrate

# 6. Crear usuario admin
php artisan db:seed --class=DatabaseSeeder

# 7. Iniciar servidor
php artisan serve
```

## 📚 Documentación Completa

Toda la documentación del sistema está en la carpeta **`docs/`**

### 📖 Documentos Principales:

- **[docs/README.md](docs/README.md)** - Descripción completa del sistema
- **[docs/INDEX.md](docs/INDEX.md)** - Índice de toda la documentación
- **[docs/INSTALACION.md](docs/INSTALACION.md)** - Guía de instalación paso a paso
- **[docs/ARQUITECTURA_SISTEMA.md](docs/ARQUITECTURA_SISTEMA.md)** - Arquitectura técnica
- **[docs/ESTRUCTURA_BASE_DATOS.md](docs/ESTRUCTURA_BASE_DATOS.md)** - Esquema de BD

### 🎯 Por Rol:

| Rol | Documentación Recomendada |
|-----|--------------------------|
| **👨‍💼 Administrador** | [INSTALACION.md](docs/INSTALACION.md), [AUTENTICACION.md](docs/AUTENTICACION.md), [BACKUPS.md](docs/BACKUPS.md) |
| **👨‍💻 Desarrollador** | [ARQUITECTURA_SISTEMA.md](docs/ARQUITECTURA_SISTEMA.md), [MIGRACIONES_MODELOS.md](docs/MIGRACIONES_MODELOS.md) |
| **👤 Usuario Final** | [README.md](docs/README.md), [GUIA_DISEÑO_UNIFICADO.md](docs/GUIA_DISEÑO_UNIFICADO.md) |

## 🛠️ Tecnologías

- **Backend**: Laravel 10, PHP 8.2.12
- **Base de Datos**: MySQL/MariaDB
- **Frontend**: Tailwind CSS, jQuery, Font Awesome
- **Servidor**: Apache (XAMPP)

## 📦 Módulos del Sistema

1. **Administración** - Usuarios, roles, configuración
2. **Producción** - Registro de producción diaria
3. **Inventario** - Control de stock con alertas
4. **Control de Salidas** - Distribución de productos
5. **Personal y Vehículos** - Gestión de recursos

## 🔔 Sistema de Alertas

Alertas automáticas de stock bajo con umbrales personalizados:

| Producto | Umbral |
|----------|--------|
| Agua (sabor/limón/natural) | < 50 unidades |
| Bolos y Gelatinas | < 25 unidades |
| Botellones e Hielo | < 5 unidades |

## 📊 Estructura del Proyecto

```
agua_colegial/
├── app/
│   ├── Http/Controllers/    # Controladores MVC
│   └── Models/               # Modelos Eloquent
├── database/
│   └── migrations/           # Migraciones de BD
├── docs/                     # 📚 Documentación completa
├── resources/
│   └── views/                # Vistas Blade
├── routes/
│   └── web.php               # Rutas del sistema
└── public/                   # Assets públicos
```

## 🔐 Credenciales por Defecto

```
Email: admin@aguacolegial.com
Password: password
```

**⚠️ IMPORTANTE**: Cambiar las credenciales en producción.

## 📞 Soporte

Para consultas técnicas, revisar la documentación en `docs/` o contactar al equipo de desarrollo.

---

**Desarrollado para**: Agua Colegial  
**Versión**: 1.0  
**Última Actualización**: Noviembre 2025

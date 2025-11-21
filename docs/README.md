# Sistema de Gestión - Agua Colegial

## Descripción
Sistema integral para la gestión de producción, inventario, personal y controles operativos de la planta Agua Colegial.

## Requisitos
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js (para assets)

## Instalación Rápida
```bash
# Clonar e instalar dependencias
composer install
npm install && npm run build

# Configurar
cp .env.example .env
php artisan key:generate

# Base de datos
php artisan migrate
php artisan db:seed
```

## Credenciales por Defecto
- **Email**: admin@aguacolegial.com
- **Contraseña**: password

## Módulos del Sistema
- **Personal**: Gestión de empleados
- **Inventario**: Control de stock
- **Producción**: Registro diario
- **Salidas**: Despachos y retornos
- **Controles**: Insumos, mantenimiento, fumigación, tanques, fosa séptica
- **Vehículos**: Gestión de flota
- **Reportes**: Generación de PDFs

## Documentación
- `INSTALACION.md` - Guía de instalación completa
- `HERRAMIENTAS.md` - Stack tecnológico
- `ESTRUCTURA_BD.md` - Base de datos
- `SESION.md` - Resumen de desarrollo

## Soporte
Sistema desarrollado para Agua Colegial - 2025

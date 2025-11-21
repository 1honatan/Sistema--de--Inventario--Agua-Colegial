# Stack Tecnológico

## Backend

### Framework
- **Laravel 10.x** - Framework PHP MVC

### Base de Datos
- **MySQL 5.7+** - Sistema de gestión de BD
- **Eloquent ORM** - Mapeo objeto-relacional

### Autenticación
- **Laravel Breeze** - Sistema de autenticación
- **Spatie Permission** - Gestión de roles y permisos

### Generación de PDFs
- **DomPDF** - Conversión HTML a PDF
- **Barryvdh/Laravel-DomPDF** - Wrapper para Laravel

## Frontend

### CSS Framework
- **Tailwind CSS 3.x** - Framework utilitario
- **Bootstrap 5** - Componentes UI

### JavaScript
- **jQuery 3.x** - Manipulación DOM
- **DataTables** - Tablas interactivas
- **Chart.js** - Gráficos y estadísticas
- **SweetAlert2** - Alertas elegantes

### Iconos
- **Font Awesome 6** - Biblioteca de iconos

## Herramientas de Desarrollo

### Gestión de Dependencias
- **Composer** - Dependencias PHP
- **NPM** - Dependencias JavaScript

### Compilación de Assets
- **Vite** - Bundler moderno
- **PostCSS** - Procesador CSS

### Control de Versiones
- **Git** - Sistema de control de versiones

## Estructura del Proyecto

```
agua_colegial/
├── app/
│   ├── Http/Controllers/    # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # Proveedores
├── config/                  # Configuraciones
├── database/
│   ├── migrations/          # Migraciones
│   └── seeders/             # Seeders
├── public/                  # Archivos públicos
├── resources/
│   ├── views/               # Vistas Blade
│   ├── css/                 # Estilos
│   └── js/                  # JavaScript
├── routes/                  # Rutas
├── storage/                 # Archivos generados
└── docs/                    # Documentación
```

## Comandos Útiles

```bash
# Artisan
php artisan serve              # Servidor desarrollo
php artisan migrate            # Ejecutar migraciones
php artisan db:seed            # Ejecutar seeders
php artisan cache:clear        # Limpiar caché
php artisan route:list         # Listar rutas

# Composer
composer install               # Instalar dependencias
composer update                # Actualizar dependencias
composer dump-autoload         # Regenerar autoload

# NPM
npm install                    # Instalar dependencias
npm run dev                    # Desarrollo
npm run build                  # Producción
```

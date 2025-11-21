# Guía de Instalación

## Requisitos del Sistema

### Software
- XAMPP 8.1+ (Apache, MySQL, PHP)
- Composer 2.x
- Node.js 16+
- Git

### Extensiones PHP
- BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalación Paso a Paso

### 1. Clonar el Proyecto
```bash
cd C:\xampp\htdocs
git clone [url-repositorio] agua_colegial
cd agua_colegial
```

### 2. Instalar Dependencias
```bash
composer install
npm install
npm run build
```

### 3. Configurar Entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar Base de Datos
Editar `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=agua_colegial_bd
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Crear Base de Datos
```bash
mysql -u root -e "CREATE DATABASE agua_colegial_bd CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Ejecutar Migraciones
```bash
php artisan migrate
php artisan db:seed
```

### 7. Configurar Permisos
```bash
chmod -R 775 storage bootstrap/cache
```

### 8. Iniciar Servidor
```bash
php artisan serve
```

Acceder a: http://localhost:8000

## Configuración de Apache (XAMPP)

### Virtual Host
Agregar en `httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/agua_colegial/public"
    ServerName aguacolegial.local
    <Directory "C:/xampp/htdocs/agua_colegial/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Hosts
Agregar en `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 aguacolegial.local
```

## Solución de Problemas

### Error de Permisos
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Error de Base de Datos
- Verificar que MySQL esté corriendo
- Verificar credenciales en `.env`
- Verificar que la BD exista

### Error 500
- Revisar `storage/logs/laravel.log`
- Verificar permisos de carpetas

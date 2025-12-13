<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3307'),
            'database' => env('DB_DATABASE', 'agua_colegial_bd_new'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                // ============================================
                // CONFIGURACIÓN ANTI-CORRUPCIÓN MÁXIMA
                // ============================================

                // Errores: Lanzar excepciones para detectar problemas inmediatamente
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                // Preparación: Usar prepared statements reales (previene SQL injection y corrupción)
                PDO::ATTR_EMULATE_PREPARES => false,

                // Tipos de datos: Preservar tipos originales (evita conversiones que corrompen datos)
                PDO::ATTR_STRINGIFY_FETCHES => false,

                // Conexiones: NO usar persistentes (previene estados corruptos entre requests)
                PDO::ATTR_PERSISTENT => false,

                // Timeout: Tiempo razonable para operaciones (previene bloqueos eternos)
                PDO::ATTR_TIMEOUT => 30,

                // Autocommit: Activado por defecto para commits inmediatos
                PDO::ATTR_AUTOCOMMIT => true,

                // Transacciones: Modo estricto
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                // ============================================
                // CONFIGURACIÓN ESPECÍFICA MYSQL
                // ============================================

                // Comandos de inicialización con máxima seguridad
                PDO::MYSQL_ATTR_INIT_COMMAND => implode('; ', [
                    // Charset y collation seguros
                    "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",

                    // SQL Mode estricto - PREVIENE CORRUPCIÓN DE DATOS
                    "SET sql_mode='STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",

                    // InnoDB en modo seguro
                    "SET innodb_strict_mode=1",

                    // Timeouts para prevenir bloqueos
                    "SET wait_timeout=28800",
                    "SET interactive_timeout=28800",

                    // Prevenir deadlocks con timeout
                    "SET innodb_lock_wait_timeout=50",

                    // Transacciones: Nivel de aislamiento REPEATABLE READ (default InnoDB)
                    "SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ",

                    // Zona horaria
                    "SET time_zone='-04:00'"
                ]),

                // Buffer de consultas: Activado para estabilidad
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,

                // Seguridad: Desactivar carga de archivos locales (previene ataques)
                PDO::MYSQL_ATTR_LOCAL_INFILE => false,

                // Compresión: Desactivada para evitar problemas de corrupción en red
                PDO::MYSQL_ATTR_COMPRESS => false,

                // Direct query: Desactivado para usar prepared statements
                PDO::MYSQL_ATTR_DIRECT_QUERY => false,

                // Encontrar filas: Devolver filas encontradas en lugar de afectadas
                PDO::MYSQL_ATTR_FOUND_ROWS => true,

                // Múltiples statements: Desactivado por seguridad
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];

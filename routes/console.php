<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/**
 * ===========================================
 * Comandos Artisan y Tareas Programadas
 * ===========================================
 *
 * Laravel 11 utiliza este archivo para registrar comandos Artisan
 * y configurar tareas programadas (cron jobs).
 */

/**
 * Comando de ejemplo: Inspirational quote
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/**
 * ===========================================
 * Tareas Programadas (Schedule)
 * ===========================================
 */

/**
 * Backup Semanal de Base de Datos
 *
 * Se ejecuta todos los domingos a las 2:00 AM
 * Comprime el backup y mantiene solo los últimos 90 días
 */
Schedule::command('backup:database --compress --keep-days=90')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->timezone('America/Bogota')
    ->onSuccess(function () {
        info('✅ Backup semanal completado exitosamente.');
    })
    ->onFailure(function () {
        info('❌ Error en backup semanal.');
    });

/**
 * Backup Diario de Base de Datos (Opcional - comentado por defecto)
 *
 * Descomenta las siguientes líneas para habilitar backups diarios.
 * Se ejecuta todos los días a las 1:00 AM.
 */
// Schedule::command('backup:database --compress --keep-days=30')
//     ->daily()
//     ->at('01:00')
//     ->timezone('America/Bogota')
//     ->onSuccess(function () {
//         info('✅ Backup diario completado exitosamente.');
//     })
//     ->onFailure(function () {
//         info('❌ Error en backup diario.');
//     });

/**
 * Limpieza de Logs Antiguos (cada mes)
 *
 * Elimina archivos de log mayores a 30 días
 */
Schedule::command('log:clear --days=30')
    ->monthly()
    ->onSuccess(function () {
        info('✅ Logs antiguos eliminados.');
    });

/**
 * Verificación de Stock Bajo (diario)
 *
 * Verifica los niveles de stock de todos los productos activos
 * y genera alertas automáticamente si están por debajo del umbral.
 * Se ejecuta todos los días a las 2:00 AM.
 */
Schedule::command('verificar:stock-bajo --umbral=10')
    ->dailyAt('02:00')
    ->timezone('America/Bogota')
    ->onSuccess(function () {
        info('✅ Verificación de stock bajo completada exitosamente.');
    })
    ->onFailure(function () {
        info('❌ Error en verificación de stock bajo.');
    });

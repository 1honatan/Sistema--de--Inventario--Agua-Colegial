<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Configurar zona horaria de Bolivia
date_default_timezone_set('America/La_Paz');

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middleware de alias
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'restrict.ip' => \App\Http\Middleware\RestrictIpAddress::class,
        ]);

        // Aplicar restricción de IP globalmente (opcional, puedes comentar si solo quieres aplicarlo en rutas específicas)
        // $middleware->append(\App\Http\Middleware\RestrictIpAddress::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejar error 419 (CSRF token expirado)
        // En lugar de mostrar error, simplemente redirigir con mensaje amigable
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Si es AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, recarga la página.',
                    'reload' => true
                ], 419);
            }

            // Si es formulario normal, redirigir atrás con mensaje
            return redirect()->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->with('warning', 'Tu sesión expiró. Por favor, intenta nuevamente. Los datos del formulario se han conservado.');
        });
    })
    ->withProviders([
        \App\Providers\TimezoneServiceProvider::class,
    ])
    ->create();

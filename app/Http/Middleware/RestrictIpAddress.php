<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para restringir acceso por dirección IP.
 *
 * Solo permite acceso desde IPs autorizadas configuradas en .env
 */
class RestrictIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener IPs permitidas desde .env (separadas por comas)
        $allowedIps = explode(',', env('ALLOWED_IPS', ''));

        // Limpiar espacios en blanco
        $allowedIps = array_map('trim', $allowedIps);

        // Obtener la IP del cliente
        $clientIp = $request->ip();

        // Si no hay IPs configuradas, permitir acceso (para desarrollo local)
        if (empty($allowedIps[0])) {
            return $next($request);
        }

        // Verificar si la IP está en la lista permitida
        // También permitir localhost y IPs de red local
        $localIps = ['127.0.0.1', '::1', 'localhost'];

        if (in_array($clientIp, array_merge($allowedIps, $localIps))) {
            return $next($request);
        }

        // Verificar si es una IP de red local (192.168.x.x, 10.x.x.x)
        if ($this->isLocalNetwork($clientIp)) {
            return $next($request);
        }

        // Registrar intento de acceso no autorizado
        \Log::warning('Intento de acceso no autorizado desde IP: ' . $clientIp, [
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
        ]);

        // Bloquear acceso
        abort(403, 'Acceso denegado. Su dirección IP no está autorizada.');
    }

    /**
     * Verificar si la IP pertenece a una red local.
     */
    private function isLocalNetwork(string $ip): bool
    {
        // Verificar rangos de red local
        $localRanges = [
            '192.168.',  // Clase C privada
            '10.',       // Clase A privada
            '172.16.',   // Clase B privada (parte)
            '172.17.',
            '172.18.',
            '172.19.',
            '172.20.',
            '172.21.',
            '172.22.',
            '172.23.',
            '172.24.',
            '172.25.',
            '172.26.',
            '172.27.',
            '172.28.',
            '172.29.',
            '172.30.',
            '172.31.',
        ];

        foreach ($localRanges as $range) {
            if (str_starts_with($ip, $range)) {
                return true;
            }
        }

        return false;
    }
}

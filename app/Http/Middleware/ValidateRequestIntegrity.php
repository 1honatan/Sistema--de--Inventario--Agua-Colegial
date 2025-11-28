<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar la integridad de las peticiones HTTP.
 *
 * Previene la inyección de datos corruptos o maliciosos mediante validación
 * estricta de todos los datos de entrada antes de procesarlos.
 */
class ValidateRequestIntegrity
{
    /**
     * Manejar una petición entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validar todos los inputs de la petición
        $this->validateRequestData($request);

        // Sanitizar los datos de entrada
        $this->sanitizeInput($request);

        return $next($request);
    }

    /**
     * Validar los datos de la petición.
     *
     * @param  Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequestData(Request $request): void
    {
        $allInput = $request->all();

        foreach ($allInput as $key => $value) {
            // Validar caracteres nulos
            if (is_string($value) && strpos($value, "\0") !== false) {
                abort(400, "Datos inválidos detectados: caracteres nulos en {$key}");
            }

            // Validar SQL injection básica
            if (is_string($value) && $this->containsSQLInjection($value)) {
                \Log::warning("Posible intento de SQL Injection detectado", [
                    'field' => $key,
                    'value' => $value,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                abort(400, "Datos inválidos detectados en {$key}");
            }

            // Validar longitud excesiva (prevenir DoS)
            if (is_string($value) && strlen($value) > 65535) {
                abort(400, "Datos demasiado largos en {$key}");
            }

            // Validar arrays recursivamente
            if (is_array($value)) {
                $this->validateArray($value, $key);
            }
        }
    }

    /**
     * Validar arrays recursivamente.
     *
     * @param  array  $array
     * @param  string  $prefix
     */
    protected function validateArray(array $array, string $prefix): void
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix . '.' . $key;

            if (is_string($value) && strpos($value, "\0") !== false) {
                abort(400, "Datos inválidos detectados: caracteres nulos en {$fullKey}");
            }

            if (is_string($value) && $this->containsSQLInjection($value)) {
                \Log::warning("Posible intento de SQL Injection detectado", [
                    'field' => $fullKey,
                    'value' => $value
                ]);
                abort(400, "Datos inválidos detectados en {$fullKey}");
            }

            if (is_array($value)) {
                $this->validateArray($value, $fullKey);
            }
        }
    }

    /**
     * Detectar patrones comunes de SQL injection.
     *
     * @param  string  $value
     * @return bool
     */
    protected function containsSQLInjection(string $value): bool
    {
        $patterns = [
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b.*\bwhere\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\binsert\b.*\binto\b.*\bvalues\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bexec\b.*\()/i',
            '/(\bexecute\b.*\()/i',
            '/(--|\#|\/\*)/',
            '/(\bor\b.*=.*)/i',
            '/(\band\b.*=.*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitizar los datos de entrada.
     *
     * @param  Request  $request
     */
    protected function sanitizeInput(Request $request): void
    {
        $sanitized = [];

        foreach ($request->all() as $key => $value) {
            $sanitized[$key] = $this->sanitizeValue($value);
        }

        $request->merge($sanitized);
    }

    /**
     * Sanitizar un valor individual.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function sanitizeValue($value)
    {
        if (is_string($value)) {
            // Remover caracteres de control
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

            // Trim espacios en blanco
            $value = trim($value);

            return $value;
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeValue'], $value);
        }

        return $value;
    }
}

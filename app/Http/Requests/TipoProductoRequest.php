<?php

declare(strict_types=1);

namespace App\Http\Requests;

/**
 * Request de validación genérico para tipos de producto.
 *
 * Alias que redirige a StoreTipoProductoRequest o UpdateTipoProductoRequest
 * según el contexto.
 *
 * Uso recomendado:
 * - Para crear: usar StoreTipoProductoRequest
 * - Para actualizar: usar UpdateTipoProductoRequest
 * - Para validaciones genéricas: usar esta clase
 */
class TipoProductoRequest extends StoreTipoProductoRequest
{
    /**
     * Determinar qué reglas usar según el método HTTP.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Si es una actualización (PUT/PATCH), usar reglas de update
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $updateRequest = new UpdateTipoProductoRequest();
            $updateRequest->setContainer($this->container);
            $updateRequest->setRedirector($this->redirector);
            $updateRequest->setRouteResolver($this->getRouteResolver());

            return $updateRequest->rules();
        }

        // Por defecto, usar reglas de creación
        return parent::rules();
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Alias del modelo Usuario para compatibilidad con Laravel Auth.
 *
 * Laravel espera que el modelo de autenticación se llame "User".
 * Esta clase extiende de Usuario para mantener toda la funcionalidad
 * mientras se mantiene la compatibilidad con el sistema de autenticación.
 */
class User extends Usuario
{
    //
}

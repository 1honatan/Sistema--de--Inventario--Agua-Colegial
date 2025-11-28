<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Trait para validar la integridad de datos antes de guardar en la base de datos.
 *
 * Previene la inserción de datos corruptos o inválidos mediante validación estricta.
 *
 * Uso:
 * - Agregar el trait a cualquier modelo: use DataIntegrity;
 * - El trait validará automáticamente los datos antes de insert/update
 */
trait DataIntegrity
{
    /**
     * Boot del trait - registrar eventos de modelo.
     */
    protected static function bootDataIntegrity()
    {
        // Validar antes de crear
        static::creating(function ($model) {
            $model->validateDataIntegrity();
        });

        // Validar antes de actualizar
        static::updating(function ($model) {
            $model->validateDataIntegrity();
        });
    }

    /**
     * Validar la integridad de los datos del modelo.
     *
     * @throws ValidationException si los datos son inválidos
     */
    protected function validateDataIntegrity()
    {
        // 1. Validar que no haya caracteres nulos inesperados
        $this->validateNoNullCharacters();

        // 2. Validar rangos de valores numéricos
        $this->validateNumericRanges();

        // 3. Validar formato de fechas
        $this->validateDateFormats();

        // 4. Validar longitud de strings
        $this->validateStringLengths();

        // 5. Validar relaciones foráneas
        $this->validateForeignKeys();
    }

    /**
     * Validar que no haya caracteres nulos inesperados.
     */
    protected function validateNoNullCharacters()
    {
        foreach ($this->getAttributes() as $key => $value) {
            if (is_string($value) && strpos($value, "\0") !== false) {
                throw ValidationException::withMessages([
                    $key => "El campo {$key} contiene caracteres inválidos (null byte)."
                ]);
            }
        }
    }

    /**
     * Validar rangos de valores numéricos.
     */
    protected function validateNumericRanges()
    {
        $casts = $this->getCasts();

        foreach ($this->getAttributes() as $key => $value) {
            if (isset($casts[$key]) && in_array($casts[$key], ['integer', 'int', 'float', 'double', 'decimal'])) {
                if (!is_null($value)) {
                    // Validar que sea un número válido
                    if (!is_numeric($value)) {
                        throw ValidationException::withMessages([
                            $key => "El campo {$key} debe ser un número válido."
                        ]);
                    }

                    // Validar rangos para enteros
                    if (in_array($casts[$key], ['integer', 'int'])) {
                        if ($value < -2147483648 || $value > 2147483647) {
                            throw ValidationException::withMessages([
                                $key => "El campo {$key} está fuera del rango permitido para enteros."
                            ]);
                        }
                    }

                    // Validar que no sea infinito o NaN
                    if (is_float($value) && (is_infinite($value) || is_nan($value))) {
                        throw ValidationException::withMessages([
                            $key => "El campo {$key} contiene un valor numérico inválido."
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Validar formato de fechas.
     */
    protected function validateDateFormats()
    {
        $dateFields = ['created_at', 'updated_at', 'deleted_at', 'fecha', 'fecha_movimiento'];

        foreach ($this->getAttributes() as $key => $value) {
            if (in_array($key, $dateFields) || str_ends_with($key, '_at') || str_ends_with($key, '_fecha')) {
                if (!is_null($value) && !strtotime($value)) {
                    throw ValidationException::withMessages([
                        $key => "El campo {$key} no contiene una fecha válida."
                    ]);
                }
            }
        }
    }

    /**
     * Validar longitud de strings según el esquema de base de datos.
     */
    protected function validateStringLengths()
    {
        $table = $this->getTable();
        $columns = DB::select("DESCRIBE {$table}");

        foreach ($columns as $column) {
            $fieldName = $column->Field;
            $fieldType = $column->Type;

            // Extraer longitud máxima si es VARCHAR
            if (preg_match('/varchar\((\d+)\)/', $fieldType, $matches)) {
                $maxLength = (int) $matches[1];
                $value = $this->getAttribute($fieldName);

                if (is_string($value) && strlen($value) > $maxLength) {
                    throw ValidationException::withMessages([
                        $fieldName => "El campo {$fieldName} excede la longitud máxima de {$maxLength} caracteres."
                    ]);
                }
            }
        }
    }

    /**
     * Validar que las claves foráneas existan en las tablas referenciadas.
     */
    protected function validateForeignKeys()
    {
        // Obtener relaciones definidas en el modelo
        $attributes = $this->getAttributes();

        foreach ($attributes as $key => $value) {
            // Si el campo termina en _id y tiene un valor, validar que exista
            if (str_ends_with($key, '_id') && !is_null($value) && $value !== 0) {
                $relatedTable = str_replace('_id', 's', $key); // Ej: producto_id -> productos

                // Verificar si existe en la tabla relacionada
                $exists = DB::table($relatedTable)->where('id', $value)->exists();

                if (!$exists) {
                    // Intenta con nombre singular
                    $relatedTable = str_replace('_id', '', $key);
                    $exists = DB::table($relatedTable)->where('id', $value)->exists();

                    if (!$exists) {
                        throw ValidationException::withMessages([
                            $key => "El registro relacionado con ID {$value} no existe en {$relatedTable}."
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Sanitizar todos los strings para prevenir inyección SQL y XSS.
     */
    public function sanitizeAttributes()
    {
        foreach ($this->getAttributes() as $key => $value) {
            if (is_string($value)) {
                // Remover caracteres de control peligrosos
                $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);

                // Escapar HTML para prevenir XSS
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    /**
     * Verificar la integridad de un registro existente.
     *
     * @return bool
     */
    public function checkIntegrity(): bool
    {
        try {
            $this->validateDataIntegrity();
            return true;
        } catch (ValidationException $e) {
            \Log::error("Error de integridad en modelo " . get_class($this), [
                'errors' => $e->errors(),
                'data' => $this->getAttributes()
            ]);
            return false;
        }
    }
}

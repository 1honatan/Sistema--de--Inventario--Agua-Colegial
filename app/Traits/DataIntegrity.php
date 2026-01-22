<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait DataIntegrity
{
    protected static function bootDataIntegrity()
    {
        static::creating(fn($model) => $model->validateDataIntegrity());
        static::updating(fn($model) => $model->validateDataIntegrity());
    }

    protected function validateDataIntegrity()
    {
        $this->validateNoNullCharacters();
        $this->validateNumericRanges();
        $this->validateDateFormats();
        $this->validateStringLengths();
        $this->validateForeignKeys();
    }

    protected function validateNoNullCharacters()
    {
        foreach ($this->getAttributes() as $key => $value) {
            if (is_string($value) && strpos($value, "\0") !== false) {
                throw ValidationException::withMessages([
                    $key => "El campo {$key} contiene caracteres inválidos."
                ]);
            }
        }
    }

    protected function validateNumericRanges()
    {
        $casts = $this->getCasts();

        foreach ($this->getAttributes() as $key => $value) {
            if (isset($casts[$key]) && in_array($casts[$key], ['integer', 'int', 'float', 'double', 'decimal'])) {
                if (!is_null($value)) {
                    if (!is_numeric($value)) {
                        throw ValidationException::withMessages([
                            $key => "El campo {$key} debe ser un número válido."
                        ]);
                    }

                    if (in_array($casts[$key], ['integer', 'int'])) {
                        if ($value < -2147483648 || $value > 2147483647) {
                            throw ValidationException::withMessages([
                                $key => "El campo {$key} está fuera del rango permitido."
                            ]);
                        }
                    }

                    if (is_float($value) && (is_infinite($value) || is_nan($value))) {
                        throw ValidationException::withMessages([
                            $key => "El campo {$key} contiene un valor numérico inválido."
                        ]);
                    }
                }
            }
        }
    }

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

    protected function validateStringLengths()
    {
        $table = $this->getTable();
        $columns = DB::select("DESCRIBE {$table}");

        foreach ($columns as $column) {
            $fieldName = $column->Field;
            $fieldType = $column->Type;

            if (preg_match('/varchar\((\d+)\)/', $fieldType, $matches)) {
                $maxLength = (int) $matches[1];
                $value = $this->getAttribute($fieldName);

                if (is_string($value) && strlen($value) > $maxLength) {
                    throw ValidationException::withMessages([
                        $fieldName => "El campo {$fieldName} excede los {$maxLength} caracteres."
                    ]);
                }
            }
        }
    }

    protected function validateForeignKeys()
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $key => $value) {
            if (str_ends_with($key, '_id') && !is_null($value) && $value !== 0) {
                $relatedTable = str_replace('_id', 's', $key);
                $exists = DB::table($relatedTable)->where('id', $value)->exists();

                if (!$exists) {
                    $relatedTable = str_replace('_id', '', $key);
                    $exists = DB::table($relatedTable)->where('id', $value)->exists();

                    if (!$exists) {
                        throw ValidationException::withMessages([
                            $key => "El registro relacionado no existe."
                        ]);
                    }
                }
            }
        }
    }

    public function sanitizeAttributes()
    {
        foreach ($this->getAttributes() as $key => $value) {
            if (is_string($value)) {
                $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    public function checkIntegrity(): bool
    {
        try {
            $this->validateDataIntegrity();
            return true;
        } catch (ValidationException $e) {
            \Log::error("Error de integridad en " . get_class($this), [
                'errors' => $e->errors(),
                'data' => $this->getAttributes()
            ]);
            return false;
        }
    }
}

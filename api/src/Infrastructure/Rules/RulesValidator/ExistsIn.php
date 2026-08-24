<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validacion Valor existente en el modelo de base de datos sin importar su estado.
 */
class ExistsIn extends Rule
{
    public const RULE = 'exists_in';
    protected $message = ":attribute no es válido o no existe.";
    protected $fillableParams = ['modelClass', 'column'];


    public function check(
        mixed $value
    ): bool
    {

        // Validar que se hayan proporcionado los parámetros necesarios
        $this->requireParameters(['modelClass', 'column']);
        /** @var string $modelClass nombre de clase de tipo `Illuminate\Database\Eloquent\Model`*/
        $modelClass = $this->parameter('modelClass');
        /** @var string $column columna en la que se desea buscar el valor*/
        $column = $this->parameter('column');

        if (!class_exists($modelClass)) {
            return false;
        }

        try {
            return $modelClass::where($column, $value)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}

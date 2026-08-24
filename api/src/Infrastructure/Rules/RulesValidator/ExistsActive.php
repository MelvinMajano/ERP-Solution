<?php

namespace Infrastructure\Rules\RulesValidator;

use Domain\Enums\GeneralStatus;
use Rakit\Validation\Rule;

/**
 * Validacion el valor debe existir en el modelo de base de datos, en el campo especifico y debe estar activo.
 */
class ExistsActive extends Rule
{
    public const RULE = 'exists_active';
    protected $message = ":attribute se encuentra en estado inactivo.";
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
            return $modelClass::where($column, $value)
                ->where('status', GeneralStatus::ACTIVE)
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}

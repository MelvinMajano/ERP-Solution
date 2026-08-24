<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

class UniqueIn extends Rule
{
    public const RULE = 'unique_in';
    protected $message = ":value ya se encuentra registrado en el sistema.";
    protected $fillableParams = ['modelClass', 'column', 'ignore_id', 'ignore_column'];


    public function check(
        mixed $value
    ): bool
    {
        // Validar que se hayan proporcionado los parámetros necesarios
        $this->requireParameters(['modelClass', 'column']);
        /** @var string $modelClass nombre de clase de tipo Illuminate\Database\Eloquent\Model*/
        $modelClass = $this->parameter('modelClass');
        /** @var string $column columna en la que se desea buscar el valor*/
        $column = $this->parameter('column');
        $ignoreId = $this->parameter('ignore_id');
        $ignoreColumn = $this->parameter('ignore_column') ?? 'id';

        if (!class_exists($modelClass)) {
            return false;
        }

        try {
            $query = $modelClass::where($column, $value);

            // Si se proporcionó ignore_id, excluir ese registro de la búsqueda
            // para que un registro no se detecte como duplicado de sí mismo al actualizar.
            if ($ignoreId !== null) {
                $query->where($ignoreColumn, '!=', $ignoreId);
            }

            return !$query->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
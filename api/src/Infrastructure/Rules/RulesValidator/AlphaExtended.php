<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validacion solo textos, numeros y caracteres -_#() con espacios
 */
class AlphaExtended extends Rule
{
    public const RULE = 'alpha_extended';
    protected $message = ":attribute solo puede contener letras, números, espacios y los caracteres -_#()";

    public function check(
        mixed $value
    ): bool
    {
        return preg_match('/^(?=.*[A-Za-zÁÉÍÓÚáéíóúñÑ])[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s\-_#()]+$/', $value);
    }
}

<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validacion permite unicamente letras, números y los caracteres ._ sin espacios
 */
class Username extends Rule
{
    public const RULE = 'username';
    protected $message = ":attribute solo puede contener letras, números y los caracteres ._ sin espacios";

    public function check(
    mixed $value
    ): bool
    {
        return !is_null($value) && preg_match('/^[a-zA-Z0-9._]+$/', $value);
    }
}

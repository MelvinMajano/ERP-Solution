<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validacion que solo permite digitos numericos (0-9)
 */
class OnlyDigits extends Rule
{
    public const RULE = 'only_digits';
    protected $message = ":attribute solo puede contener dígitos numéricos.";

    public function check(
        mixed $value
    ): bool
    {
        if (empty($value)) return true;

        return (bool) preg_match('/^\d+$/', (string) $value);
    }
}

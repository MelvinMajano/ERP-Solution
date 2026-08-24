<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validacion para numeros de telefono
 * Acepta: digitos, +, -, espacios y numeros del 0 al 9.
 */
class PhoneNumber extends Rule
{
    public const RULE = 'phone_number';
    protected $message = ":attribute no es un formato de teléfono válido. Use solo dígitos, +, -, espacios y paréntesis.";

    public function check(
        mixed $value
    ): bool
    {
        if (empty($value)) return true;

        $value = trim($value);

        if (strlen($value) < 8) return false;
        /**
         * Toma como validas las siguientes combinaciones Hondureñas
         * 99999999
         * 9999-9999
         * +504 9999-9999
         * +504 99999999
         * 504 99999999
         */
        return (bool) preg_match('/^(\+?\d{1,4}[- ])?(\d{4}[- ]?\d{4}|\d{8})$/', $value);
    }
}

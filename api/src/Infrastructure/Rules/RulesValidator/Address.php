<?php

namespace POS\Validators\Rules;

use Rakit\Validation\Rule;

/**
 * Validación para direcciones: permite letras, números, espacios, puntos, comas, guiones y #.
 * Diseñada para prevenir caracteres especiales comunes en ataques de inyección.
 */
class Address extends Rule
{
    public const RULE = 'address';

    protected $message = ":attribute solo puede contener letras, números, espacios, puntos, comas, guiones y #";

    public function check(
        mixed $value
    ): bool
    {
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s\.,\-#]+$/u', $value);
    }
}

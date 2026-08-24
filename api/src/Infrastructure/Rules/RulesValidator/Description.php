<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Validación para descripciones: permite letras, números, espacios, puntos y comas.
 * Diseñada para prevenir caracteres especiales comunes en ataques de inyección.
 */
class Description extends Rule
{
    public const RULE = 'description';
    
    protected $message = ":attribute solo puede contener letras, números, espacios, puntos y comas";

    public function check(
        mixed $value
        ): bool
    {
        // Solo permite: Alfanuméricos (incluye acentos y ñ), espacios, puntos y comas.
        // El flag /u es vital para el soporte de UTF-8 (acentos).
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ0-9\s\.,]+$/u', $value);
    }
}
<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;

/**
 * Politica de contraseña segura
 */
class SecurePassword extends Rule
{
    public const RULE = 'secure_password';

    protected $message = ":attribute debe contener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un símbolo.";

    public function check(
        mixed $value
    ): bool
    {
        return is_string($value)
            && preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $value);
    }
}

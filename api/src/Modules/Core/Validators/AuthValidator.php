<?php

namespace Modules\Core\Validators;

use Infrastructure\Base\BaseValidator;
use Infrastructure\Exceptions\ValidationException;

/**
 * Validador de Infraestructura para el proceso de Autenticación (Login en 2 Pasos).
 *
 * Utiliza BaseValidator para cargar reglas personalizadas de Rakit Validator.
 */
class AuthValidator extends BaseValidator
{
    /**
     * Alias descriptivos para los atributos en las respuestas de error.
     * 
     * @var array<string, string>
     */
    private const ALIAS = [
        'email'     => 'correo electrónico',
        'tenant_id' => 'empresa',
        'password'  => 'contraseña',
    ];

    /**
     * Valida la estructura y formato para el Paso 1: Verificación de correo.
     *
     * @param array<string, mixed>|null $data Petición HTTP a validar.
     * @return array<string, mixed> Datos limpios y estructurados.
     * 
     * @throws ValidationException Si alguna regla de formato falla.
     */
    public static function checkEmailValidation(?array $data): array
    {
        $rules = [
            'email' => 'required|max:150|email',
        ];

        $validation = self::makeValidator($data, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    /**
     * Valida la estructura y formato para el Paso 2: Autenticación con contraseña.
     *
     * @param array<string, mixed>|null $data Petición HTTP a validar.
     * @return array<string, mixed> Datos limpios y estructurados.
     * 
     * @throws ValidationException Si alguna regla de formato falla.
     */
    public static function loginPasswordValidation(?array $data): array
    {
        $rules = [
            'tenant_id' => 'required|numeric',
            'password'  => 'required',
        ];

        $validation = self::makeValidator($data, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}
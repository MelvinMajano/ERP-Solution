<?php

namespace Modules\Core\Validators;

use Domain\Entities\Tenant;
use Domain\Entities\User;
use Infrastructure\Base\BaseValidator;
use Infrastructure\Exceptions\ValidationException;

/**
 * Validador de Infraestructura para el proceso de Registro/Onboarding.
 *
 * Utiliza BaseValidator para cargar reglas personalizadas de Rakit Validator.
 */
class OnboardingValidator extends BaseValidator
{
    /**
     * Alias descriptivos para los atributos en las respuestas de error.
     * 
     * @var array<string, string>
     */
    private const ALIAS = [
        'company_name' => 'nombre de la empresa',
        'subdomain'    => 'subdominio',
        'first_names'  => 'nombres',
        'last_names'   => 'apellidos',
        'username'     => 'nombre de usuario',
        'email'        => 'correo electrónico',
        'password'     => 'contraseña',
    ];

    /**
     * Valida la estructura, presencia y restricciones básicas de formato para el registro inicial.
     *
     * @param array<string, mixed>|null $data Petición HTTP a validar.
     * @return array<string, mixed> Datos limpios y estructurados.
     * 
     * @throws ValidationException Si alguna regla de formato falla.
     */
    public static function registerValidation(?array $data): array
    {
        $rules = [
            'company_name' => 'required|max:150|alpha_extended',
            'subdomain'    => 'required|max:50|alpha_dash|unique_in:' . Tenant::class . ',subdomain',
            'first_names'  => 'required|max:100|alpha_extended',
            'last_names'   => 'required|max:100|alpha_extended',
            'username'     => 'required|max:50|username|unique_in:' . User::class . ',username',
            'email'        => 'required|max:150|email|unique_in:' . User::class . ',email',
            'password'     => 'required|secure_password',
        ];

        $validation = self::makeValidator($data, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}
<?php

namespace Modules\Billing\Validators;

use Domain\Entities\Customer;
use Infrastructure\Base\BaseValidator;

/**
 * Motor de validación de reglas de entrada para las peticiones HTTP de Clientes.
 */
class CustomerValidator extends BaseValidator
{
    private const ALIAS = [
        'id'        => 'identificador del cliente',
        'rtn'       => 'RTN del cliente',
        'name'      => 'nombre o razón social',
        'email'     => 'correo electrónico',
        'phone'     => 'número telefónico',
        'address'   => 'dirección física',
        'is_active' => 'estado activo',
    ];
    /**
     * Valida la estructura y restricciones de unicidad para la creación de un cliente.
     */
    public static function createValidation(?array $data): array
    {
        $rules = [
            'rtn'       => 'nullable|digits:14|unique_in:' . Customer::class . ',rtn',
            'name'      => 'required|max:150|alpha_extended',
            'email'     => 'nullable|email|max:100',
            'phone'     => 'nullable|phone_number',
            'address'   => 'nullable|address|max:255',
            'is_active' => 'nullable|boolean',
        ];

        $validation = self::makeValidator($data ?? [], $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    /**
     * Valida la actualización parcial o total de datos del cliente excluyendo su propio ID.
     */
    public static function updateValidation(int $id, ?array $data): array
    {
        $payload = array_merge($data ?? [], ['id' => $id]);

        $rules = [
            'id'        => 'required|integer|min:1',
            'rtn'       => 'nullable|digits:14|unique_in:' . Customer::class . ',rtn,' . $id,
            'name'      => 'nullable|max:150|alpha_extended',
            'email'     => 'nullable|email|max:100',
            'phone'     => 'nullable|phone_number',
            'address'   => 'nullable|address|max:255',
            'is_active' => 'nullable|boolean',
        ];

        $validation = self::makeValidator($payload, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}
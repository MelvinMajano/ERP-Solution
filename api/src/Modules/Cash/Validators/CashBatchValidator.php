<?php

namespace Modules\Cash\Validators;

use Infrastructure\Base\BaseValidator;

class CashBatchValidator extends BaseValidator
{
    public const ALIAS = [
        'id'              => 'identificador de turno',
        'cashier_user_id' => 'cajero asignado',
        'opening_balance' => 'monto inicial de apertura',
        'actual_amount'   => 'monto físico reportado',
    ];

    public static function openValidation(?array $data): array
    {
        $rules = [
            'cashier_user_id' => 'required|integer|min:1',
            'opening_balance' => 'required|numeric|min:0|max:9999999999.99',
        ];

        $validation = self::makeValidator($data ?? [], $rules);
        if (defined('static::ALIAS')) {
            $validation->setAliases(static::ALIAS);
        }
        $validation->validate();

        return static::validationCheck($validation);
    }

    public static function closeValidation(int $id, ?array $data): array
    {
        $payload = array_merge($data ?? [], ['id' => $id]);

        $rules = [
            'id'            => 'required|integer|min:1',
            'actual_amount' => 'required|numeric|min:0|max:9999999999.99',
        ];

        $validation = self::makeValidator($payload, $rules);
        if (defined('static::ALIAS')) {
            $validation->setAliases(static::ALIAS);
        }
        $validation->validate();

        return static::validationCheck($validation);
    }
}
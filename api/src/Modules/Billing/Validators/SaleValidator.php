<?php

namespace Modules\POS\Validators;

use Domain\Entities\Customer;
use Domain\Entities\Product;
use Domain\Entities\User;
use Infrastructure\Base\BaseValidator;

class SaleValidator extends BaseValidator
{
    private const ALIAS = [
        'customer_id'       => 'cliente',
        'cashier_user_id'   => 'cajero',
        'cash_batch_id'     => 'lote/caja de efectivo',
        'notes'             => 'notas de la factura',
        'items'             => 'ítems de la venta',
        'items.*.product_id' => 'producto del detalle',
        'items.*.quantity'   => 'cantidad',
        'items.*.unit_price' => 'precio unitario',
        'items.*.discount'   => 'descuento',
    ];

    /**
     * Valida el payload necesario para registrar una venta completa con su detalle.
     *
     * @param array<string, mixed>|null $data
     * @return array<string, mixed> Datos filtrados y validados.
     */
    public static function createValidation(?array $data): array
    {
        $rules = [
            'customer_id'        => 'required|integer|exists_active:' . Customer::class . ',id',
            'cashier_user_id'    => 'required|integer|exists_active:' . User::class . ',id',
            'cash_batch_id'      => 'nullable|integer',
            'notes'              => 'nullable|max:255',
            'items'              => 'required|array',
            'items.*.product_id' => 'required|integer|exists_active:' . Product::class . ',id',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0',
        ];

        $validation = self::makeValidator($data, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}
<?php

namespace Modules\Inventory\Validators;

use Domain\Entities\Product;
use Domain\Entities\Supplier;
use Infrastructure\Base\BaseValidator;

/**
 * Motor de validación de reglas de entrada para las peticiones HTTP de Productos.
 */
class ProductValidator extends BaseValidator
{
    private const ALIAS = [
        'id'                  => 'identificador del producto',
        'primary_supplier_id' => 'proveedor principal',
        'sku'                 => 'SKU',
        'barcode'             => 'código de barra',
        'name'                => 'nombre',
        'price'               => 'precio base',
        'cost'                => 'costo unitario',
        'current_stock'       => 'stock actual',
        'is_service'          => 'es un servicio',
        'is_active'           => 'estado activo',
    ];

    /**
     * Valida la estructura y restricciones de unicidad para la creación de un producto.
     */
    public static function createValidation(?array $data): array
    {
        $rules = [
            'primary_supplier_id' => 'nullable|integer|exists_active:' . Supplier::class . ',id',
            'sku'                 => 'required|max:50|alpha_dash|unique_in:' . Product::class . ',sku',
            'barcode'             => 'nullable|max:100|alpha_num|unique_in:' . Product::class . ',barcode',
            'name'                => 'required|max:150|alpha_extended',
            'price'               => 'required|numeric|min:0|max:999999998',
            'cost'                => 'required|numeric|min:0|max:999999998',
            'current_stock'       => 'nullable|numeric|min:0',
            'is_service'          => 'nullable|boolean',
            'is_active'           => 'nullable|boolean',
        ];

        $validation = self::makeValidator($data ?? [], $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    /**
     * Valida la actualización parcial o total de datos del producto excluyendo su propio ID.
     */
    public static function updateValidation(int $id, ?array $data): array
    {
        $payload = array_merge($data ?? [], ['id' => $id]);

        $rules = [
            'id'                  => 'required|integer|min:1',
            'primary_supplier_id' => 'nullable|integer|exists_active:' . Supplier::class . ',id',
            'sku'                 => 'nullable|max:50|alpha_dash|unique_in:' . Product::class . ',sku,' . $id,
            'barcode'             => 'nullable|max:100|alpha_num|unique_in:' . Product::class . ',barcode,' . $id,
            'name'                => 'nullable|max:150|alpha_extended',
            'price'               => 'nullable|numeric|min:0|max:999999998',
            'cost'                => 'nullable|numeric|min:0|max:999999998',
            'current_stock'       => 'nullable|numeric|min:0',
            'is_service'          => 'nullable|boolean',
        ];

        $validation = self::makeValidator($payload, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}
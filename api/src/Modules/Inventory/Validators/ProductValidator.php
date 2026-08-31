<?php

namespace Modules\Inventory\Validators;

use Domain\Entities\Product;
use Domain\Entities\Supplier;
use Infrastructure\Base\BaseValidator;

class ProductValidator extends BaseValidator
{
    private const ALIAS = [
        'id'                  => 'identificador del producto',
        'primary_supplier_id' => 'proveedor principal',
        'sku'                 => 'SKU',
        'barcode'             => 'codigo de barra',
        'name'                => 'nombre',
        'price'               => 'precio base',
        'cost'                => 'costo unitario',
        'current_stock'       => 'stock actual',
        'is_service'          => 'es un servicio',
        'is_active'           => 'estado activo'
    ];

    /**
     * Valida los parámetros de consulta para la paginación y filtrado.
     */
    public static function getValidation(array $queryParams):array
    {
        $rules=[
            'page'      => 'nullable|integer|min:1',
            'pageSize'  => 'nullable|integer|min:1|max:100',
            'sortBy'    => 'nullable|string',
            'sortDir'   => 'nullable|string|in:asc,desc,ASC,DESC',
            'filters'   => 'nullable|array',
        ];

        $validation = self::makeValidator($queryParams,$rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    //se encarga de asegurar que el id necesario para getById sea valido.
    public static function getByIdValidation(int $id):array
    {
        $rules =[
            'id'=>'required|integer|min:1'
        ];

        $validation = self::makeValidator(['id'=>$id],$rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    //Se encarga de validar la data que se necesita en el createProduct
    public static function createValidation(?array $data): array
    {
        $rules=[
            'primary_supplier_id'=>'nullable|integer|exists_active:' . Supplier::class . ',id',
            'sku'                =>'required|max:50|alpha_dash|unique_in:' . Product::class . ',sku',
            'barcode'            =>'nullable|max:100|alpha_num|unique_in:' . Product::class . ',barcode',
            'name'               =>'required|max:150|alpha_extended',
            'price'              =>'required|numeric|min:0|max:999999998',
            'cost'               =>'required|numeric|min:0|max:999999998',
            'current_stock'      =>'nullable|numeric|min:0',
            'is_service'         =>'nullable|boolean',
            'is_active'          =>'nullable|boolean',
        ];

        $validation = self::makeValidator($data, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    //Se encarga de validar la data que se necesita en el UpdateProduct
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
            'is_active'           => 'nullable|boolean',
        ];

        $validation = self::makeValidator($payload, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    //Se encarga de validar la data que se necesita en el setStatuProduct
    public static function setStatusValidation(int $id, ?array $data): array
    {
        $payload = array_merge($data ?? [], ['id' => $id]);

        $rules = [
            'id'        => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];

        $validation = self::makeValidator($payload, $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }

    //Se encarga de validar que id por medio del cual se eliminara el producto ese valido y es obligatorio
    public static function deleteValidation(int $id): array
    {
        $rules = [
            'id' => 'required|integer|min:1',
        ];

        $validation = self::makeValidator(['id' => $id], $rules);
        $validation->setAliases(self::ALIAS);
        $validation->validate();

        return static::validationCheck($validation);
    }
}

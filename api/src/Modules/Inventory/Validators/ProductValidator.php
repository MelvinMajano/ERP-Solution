<?php

namespace Modules\Inventory\Validators;

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
}

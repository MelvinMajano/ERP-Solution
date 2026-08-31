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
}

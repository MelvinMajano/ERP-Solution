<?php

namespace Modules\Inventory\Transformers;

use Domain\Entities\Product;
use Infrastructure\Base\BaseTransformer;

class ProductTransformer extends BaseTransformer
{
    /**
     * Convierte la entidad Product a un array representativo.
     *
     * @param Product $item
     * @return array
     */
    public function transform(mixed $item): array
    {
        /** @var Product $item */
        return [
            'id'                  => $item->id,
            'primary_supplier_id' => $item->primary_supplier_id, 
            'sku'                 => $item->sku,
            'barcode'             => $item->barcode,
            'name'                => $item->name,
            'price'               => (float) $item->price,
            'cost'                => (float) $item->cost,
            'current_stock'       => (float) $item->current_stock, 
            'is_service'          => (bool) $item->is_service,     
            'is_active'           => (bool) $item->is_active,      
            'created_at'          => $item->created_at?->format('Y-m-d H:i:s'), 
            'updated_at'          => $item->updated_at?->format('Y-m-d H:i:s'), 
        ];
    }
}
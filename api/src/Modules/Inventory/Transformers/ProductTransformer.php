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
     */
    public function transform(mixed $item):array
    {
        /** @var Product $item */
        return [
            'id'                  => $item->id,
            'primary_supplier_id' => $item->primarySupplierId,
            'sku'                 => $item->sku,
            'barcode'             => $item->barcode,
            'name'                => $item->name,
            'price'               => (float) $item->price,
            'cost'                => (float) $item->cost,
            'current_stock'       => (float) $item->currentStock,
            'is_service'          => (bool) $item->isService,
            'is_active'           => (bool) $item->isActive,
            'created_at'          => $item->createdAt?->format('Y-m-d H:i:s'),
            'updated_at'          => $item->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
<?php

namespace Modules\Billing\Transformers;

use Domain\Entities\Customer;
use Infrastructure\Base\BaseTransformer;

/**
 * Mapea la entidad Customer a la estructura de representación JSON pública.
 * 
 * Garantiza el formateo a tipos de datos nativos en respuestas API
 * y convierte la nomenclatura snake_case a camelCase para el cliente HTTP.
 */
class CustomerTransformer extends BaseTransformer
{
    /**
     * Transforma una entidad Customer en un arreglo asociativo para la respuesta API.
     *
     * @param mixed $item Debe ser una instancia de Customer.
     * @return array<string, mixed>
     */
    public function transform(mixed $item): array
    {
        /** @var Customer $item */
        return [
            'id'        => (int) $item->id,
            'rtn'       => $item->rtn,
            'name'      => $item->name,
            'email'     => $item->email,
            'phone'     => $item->phone,
            'address'   => $item->address,
            'isActive'  => (bool) $item->is_active,
            'createdAt' => $item->created_at?->toIso8601String(),
            'updatedAt' => $item->updated_at?->toIso8601String(),
        ];
    }
}
<?php

namespace Modules\Inventory\Transformers;

use Domain\Entities\MovementReason;
use Infrastructure\Base\BaseTransformer;

/**
 * Transformador de Salida para la Entidad MovementReason.
 *
 * Mapea las propiedades de la entidad Eloquent a una representación JSON estandarizada
 * para la respuesta HTTP de la API, manteniendo la convención camelCase.
 */
class MovementReasonTransformer extends BaseTransformer
{
    /**
     * Transforma una instancia de MovementReason en un arreglo serializable.
     *
     * @param mixed $item Modelo de entidad de tipo MovementReason.
     * @return array{
     *     id: int,
     *     name: string,
     *     movementType: string,
     *     isActive: bool,
     *     createdAt: string|null
     * } Arreglo estructurado para la respuesta JSON.
     */
    public function transform(mixed $item): array
    {
        /** @var MovementReason $item */
        return [
            'id'           => $item->id,
            'name'         => $item->name,
            'movementType' => $item->movement_type,
            'isActive'     => (bool) $item->is_active,
            'createdAt'    => $item->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
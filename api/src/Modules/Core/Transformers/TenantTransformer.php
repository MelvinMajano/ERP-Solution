<?php

namespace Modules\Core\Transformers;

use Domain\Entities\Tenant;
use Infrastructure\Base\BaseTransformer;

/**
 * Mapea la entidad Tenant a la estructura JSON pública de respuesta.
 */
class TenantTransformer extends BaseTransformer
{
    /**
     * Transforma una instancia de Tenant a un arreglo público.
     *
     * @param mixed $item Debe ser una instancia de Tenant.
     * @return array<string, mixed>
     */
    public function transform(mixed $item): array
    {
        /** @var Tenant $item */
        return [
            'id'           => $item->id,
            'company_name' => $item->company_name,
            'subdomain'    => $item->subdomain,
            'is_active'    => (bool) ($item->is_active ?? true),
            'created_at'   => $item->created_at?->toIso8601String(),
        ];
    }
}
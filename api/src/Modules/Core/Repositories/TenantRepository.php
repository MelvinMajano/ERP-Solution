<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\TenantRepositoryInterface;
use Domain\Entities\Tenant;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación de persistencia para Tenants dentro del módulo Core.
 *
 * Extiende de BaseRepository para heredar las operaciones CRUD estandarizadas.
 */
class TenantRepository extends BaseRepository implements TenantRepositoryInterface
{
    /**
     * Constructor del repositorio.
     *
     * @param Tenant $model Inyección de la entidad Tenant.
     */
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function findBySubdomain(string $subdomain): ?Tenant
    {
        /** @var Tenant|null */
        return $this->query()
            ->where('subdomain', strtolower(trim($subdomain)))
            ->first();
    }
}
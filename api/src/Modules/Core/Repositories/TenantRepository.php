<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\TenantRepositoryInterface;
use Domain\Entities\Tenant;
use Infrastructure\Base\BaseRepository;

class TenantRepository extends BaseRepository implements TenantRepositoryInterface
{
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }

    public function findBySubdomain(string $subdomain): ?Tenant
    {
        return $this->query()
            ->where('subdomain', strtolower(trim($subdomain)))
            ->first();
    }
}
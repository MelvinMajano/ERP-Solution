<?php

namespace Domain\Contracts;

use Domain\Entities\Tenant;
use Infrastructure\Contracts\RepositoryInterface;

interface TenantRepositoryInterface extends RepositoryInterface
{
    public function findBySubdomain(string $subdomain): ?Tenant;
}
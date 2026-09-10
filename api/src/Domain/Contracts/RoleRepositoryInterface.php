<?php

namespace Domain\Contracts;

use Infrastructure\Contracts\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function assignPermissions(int $roleId, array $permissionIds): void;
}
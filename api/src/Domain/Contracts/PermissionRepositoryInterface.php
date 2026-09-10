<?php

namespace Domain\Contracts;

use Infrastructure\Contracts\RepositoryInterface;

interface PermissionRepositoryInterface extends RepositoryInterface
{
    public function getAllPermissionIds(): array;
}
<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\PermissionRepositoryInterface;
use Domain\Entities\Permission;
use Infrastructure\Base\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getAllPermissionIds(): array
    {
        return $this->query()->pluck('id')->toArray();
    }
}
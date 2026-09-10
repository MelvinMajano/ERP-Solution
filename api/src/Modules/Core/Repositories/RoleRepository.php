<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\RoleRepositoryInterface;
use Domain\Entities\Role;
use Illuminate\Database\Capsule\Manager as DB;
use Infrastructure\Base\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function assignPermissions(int $roleId, array $permissionIds): void
    {
        $records = array_map(function ($permissionId) use ($roleId) {
            return [
                'rol_id' => $roleId,
                'permission_id' => $permissionId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }, $permissionIds);

        DB::table('rol_permissions')->insert($records);
    }
}
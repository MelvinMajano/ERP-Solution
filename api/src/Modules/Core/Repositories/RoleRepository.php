<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\RoleRepositoryInterface;
use Domain\Entities\Role;
use Illuminate\Database\Capsule\Manager as DB;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación de persistencia para Roles dentro del módulo Core.
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * Constructor del repositorio.
     *
     * @param Role $model Inyección de la entidad Role.
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     *
     * Realiza un bulk insert en la tabla pivote 'rol_permissions'.
     */
    public function assignPermissions(int $roleId, array $permissionIds): void
    {
        $now = date('Y-m-d H:i:s');

        $records = array_map(static function (int $permissionId) use ($roleId, $now): array {
            return [
                'rol_id'        => $roleId,
                'permission_id' => $permissionId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }, $permissionIds);

        DB::table('rol_permissions')->insert($records);
    }
}
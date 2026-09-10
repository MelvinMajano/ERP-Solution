<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\PermissionRepositoryInterface;
use Domain\Entities\Permission;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación de persistencia para el catálogo de Permisos en el módulo Core.
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * Constructor del repositorio.
     *
     * @param Permission $model Inyección de la entidad Permission.
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllPermissionIds(): array
    {
        return $this->query()->pluck('id')->toArray();
    }
}
<?php

namespace Domain\Contracts;

use Infrastructure\Contracts\RepositoryInterface;

/**
 * Contrato de repositorio para la entidad Role.
 *
 * Maneja la gestión de roles e interacción con tablas pivot de permisos.
 */
interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * Asigna masivamente una lista de permisos a un rol específico.
     *
     * @param int $roleId Identificador único del rol.
     * @param array<int, int> $permissionIds Listado de IDs de permisos a vincular.
     * @return void
     */
    public function assignPermissions(int $roleId, array $permissionIds): void;
}
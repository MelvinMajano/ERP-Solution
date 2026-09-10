<?php

namespace Domain\Contracts;

use Infrastructure\Contracts\RepositoryInterface;

/**
 * Contrato de repositorio para el catálogo global de Permisos.
 */
interface PermissionRepositoryInterface extends RepositoryInterface
{
    /**
     * Obtiene una lista plana con todos los identificadores de permisos del sistema.
     *
     * Utilizado principalmente para la asignación completa de permisos en el Onboarding.
     *
     * @return array<int, int> Arreglo numérico con los IDs de todos los permisos.
     */
    public function getAllPermissionIds(): array;
}
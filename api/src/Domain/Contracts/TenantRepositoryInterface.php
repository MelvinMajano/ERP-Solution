<?php

namespace Domain\Contracts;

use Domain\Entities\Tenant;
use Infrastructure\Contracts\RepositoryInterface;

/**
 * Contrato de repositorio para la entidad Tenant.
 *
 * Define operaciones específicas de persistencia y consulta para clientes SaaS.
 */
interface TenantRepositoryInterface extends RepositoryInterface
{
    /**
     * Busca un Tenant en la base de datos por su subdominio.
     *
     * @param string $subdomain Subdominio a consultar.
     * @return Tenant|null Retorna la entidad si existe o null si no se encuentra.
     */
    public function findBySubdomain(string $subdomain): ?Tenant;
}
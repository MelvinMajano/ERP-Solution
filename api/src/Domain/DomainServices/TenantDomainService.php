<?php

namespace Domain\DomainServices;

use Domain\Contracts\TenantRepositoryInterface;
use Domain\Contracts\UserRepositoryInterface;
use Domain\Exceptions\DomainException;

/**
 * Servicio de Dominio encargado de ejecutar y validar las reglas de negocio
 * e invariantes de unicidad para la entidad Tenant y sus usuarios.
 */
class TenantDomainService
{
    /**
     * Constructor con inyección de contratos de repositorio.
     *
     * @param TenantRepositoryInterface $tenantRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Evalúa si un subdominio ya existe en el sistema.
     *
     * @param string $subdomain Subdominio a comprobar.
     * @return void
     * 
     * @throws DomainException Si el subdominio ya está registrado.
     */
    public function validateUniqueSubdomain(string $subdomain): void
    {
        $tenant = $this->tenantRepository->findBySubdomain($subdomain);

        if ($tenant !== null) {
            throw new DomainException("El subdominio '{$subdomain}' ya se encuentra registrado.");
        }
    }

    /**
     * Evalúa si un correo electrónico ya está registrado globalmente.
     *
     * @param string $email Correo a comprobar.
     * @return void
     * 
     * @throws DomainException Si el correo electrónico ya está registrado.
     */
    public function validateUniqueEmail(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user !== null) {
            throw new DomainException("El correo electrónico '{$email}' ya se encuentra en uso.");
        }
    }

    /**
     * Evalúa si un nombre de usuario ya está registrado globalmente.
     *
     * @param string $username Nombre de usuario a comprobar.
     * @return void
     * 
     * @throws DomainException Si el nombre de usuario ya no está disponible.
     */
    public function validateUniqueUsername(string $username): void
    {
        $user = $this->userRepository->findByUsername($username);

        if ($user !== null) {
            throw new DomainException("El nombre de usuario '{$username}' ya no está disponible.");
        }
    }

    /**
     * Ejecuta el conjunto completo de validaciones de unicidad para el alta del Tenant.
     *
     * @param string $subdomain Subdominio a validar.
     * @param string $email Correo del administrador a validar.
     * @param string $username Usuario administrador a validar.
     * @return void
     * 
     * @throws DomainException Si alguna regla de unicidad es violada.
     */
    public function validateOnboardingUniqueness(string $subdomain, string $email, string $username): void
    {
        $this->validateUniqueSubdomain($subdomain);
        $this->validateUniqueEmail($email);
        $this->validateUniqueUsername($username);
    }
}
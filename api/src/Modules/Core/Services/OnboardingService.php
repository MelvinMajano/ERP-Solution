<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Domain\Contracts\PermissionRepositoryInterface;
use Domain\Contracts\RoleRepositoryInterface;
use Domain\Contracts\TenantRepositoryInterface;
use Domain\Contracts\UserRepositoryInterface;
use Domain\DomainServices\TenantDomainService;
use Domain\Entities\Tenant;
use Infrastructure\Base\BaseService;
use Modules\Core\DTOs\RegisterTenantDTO;

/**
 * Servicio de Aplicación encargada de orquestar el flujo de alta de un nuevo Tenant,
 * delegando la ejecución de reglas de negocio a TenantDomainService y la persistencia a los repositorios.
 */
class OnboardingService extends BaseService
{
    /**
     * Constructor con inyección de contratos de repositorio y servicio de dominio.
     *
     * @param TenantRepositoryInterface $tenantRepository
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     * @param TenantDomainService $tenantDomainService
     */
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly TenantDomainService $tenantDomainService
    ) {}

    /**
     * Coagula el registro atómico de un nuevo cliente (Tenant), su Rol principal,
     * la asignación de permisos globales y su Usuario Administrador inicial.
     *
     * @param RegisterTenantDTO $dto Objeto con los datos del registro.
     * @return Tenant Entidad Tenant creada.
     * 
     * @throws \Throwable Si ocurre una falla en la transacción o reglas de negocio.
     */
    public function registerTenant(RegisterTenantDTO $dto): Tenant
    {
        // 1. Delegar comprobaciones de negocio al Domain Service (Lanza DomainException)
        $this->tenantDomainService->validateOnboardingUniqueness(
            $dto->subdomain,
            $dto->email,
            $dto->username
        );

        // 2. Orquestar la persistencia usando la abstracción de transacción de BaseService
        return $this->transaction(function () use ($dto): Tenant {
            // A. Persistir el Tenant
            /** @var Tenant $tenant */
            $tenant = $this->tenantRepository->create($dto->toTenantArray());

            // B. Crear Rol Administrador ligado al nuevo Tenant
            $adminRole = $this->roleRepository->create([
                'tenant_id'   => $tenant->id,
                'name'        => 'Administrador',
                'description' => 'Acceso total a los módulos y configuraciones de la empresa.',
                'is_active'   => true,
            ]);

            // C. Obtener catálogo de permisos y asociarlos al rol
            $allPermissionIds = $this->permissionRepository->getAllPermissionIds();
            $this->roleRepository->assignPermissions($adminRole->id, $allPermissionIds);

            // D. Hashear contraseña y persistir el Usuario Root
            $hashedPassword = password_hash($dto->password, PASSWORD_BCRYPT);
            
            $this->userRepository->create(
                $dto->toUserArray($tenant->id, $adminRole->id, $hashedPassword)
            );

            return $tenant;
        });
    }
}
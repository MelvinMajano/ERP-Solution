<?php

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Contracts\UserRepositoryInterface;
use Domain\Contracts\RoleRepositoryInterface;
use Domain\Contracts\PermissionRepositoryInterface;
use Domain\Contracts\TenantRepositoryInterface;
use Modules\Inventory\Repositories\ProductRepository;
use Modules\Core\Repositories\TenantRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Core\Repositories\RoleRepository;
use Modules\Core\Repositories\PermissionRepository;
use function DI\autowire;

/**
 * REGISTRO DE DEPENDENCIAS (PHP-DI)
 * -------------------------------------------------------------------------
 * Cada interfaz definida en `src/Domain/Contracts/` que sea inyectada en un 
 * servicio o servicio de dominio DEBE estar registrada en este arreglo.
 * 
 * Ejemplo para un nuevo módulo/recurso:
 *   SupplierRepositoryInterface::class => autowire(SupplierRepository::class),
 * -------------------------------------------------------------------------
 */
return[
    // Mapeo de la Interfaz de Dominio con su Implementación Concreta de Repositorio
    ProductRepositoryInterface::class => autowire(ProductRepository::class),
    TenantRepositoryInterface::class => DI\autowire(TenantRepository::class),
    UserRepositoryInterface::class => DI\autowire(UserRepository::class),
    RoleRepositoryInterface::class => DI\autowire(RoleRepository::class),
    PermissionRepositoryInterface::class => DI\autowire(PermissionRepository::class),
];
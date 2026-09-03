<?php

use Domain\Contracts\ProductRepositoryInterface;
use Modules\Inventory\Repositories\ProductRepository;
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
    ProductRepositoryInterface::class => autowire(ProductRepository::class)
];
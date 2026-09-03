<?php

use Domain\Contracts\ProductRepositoryInterface;
use Modules\Inventory\Repositories\ProductRepository;
use function DI\autowire;

return[
    // Mapeo de la Interfaz de Dominio con su Implementación Concreta de Repositorio
    ProductRepositoryInterface::class => autowire(ProductRepository::class)
];
<?php

namespace Domain\DomainServices;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Exceptions\DomainException;

/**
 * El objetivo de @ProductDomainService es mantener y ejecutar las reglas de negocio, que no son invariables 
 * de producto
 */
class ProductDomainService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    )
    {}
    /**
     * Se encarga de validar si el Sku es unico en la base de datos
     */
    public function validateUniqueSku(
        string $sku,
        int|string|null $ignoredId = null
    ):void
    {
        if($this->productRepository->isSkuExists($sku,$ignoredId)){
            throw new DomainException("EL SKU '{$sku}'ya se encuentra registrado.");
        }
    }
}
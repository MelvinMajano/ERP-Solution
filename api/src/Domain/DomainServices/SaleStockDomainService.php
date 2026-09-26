<?php

namespace Domain\DomainServices;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Exceptions\DomainException;

class SaleStockDomainService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Valida existencias y aplica el descuento en memoria para una lista de ítems.
     *
     * @param array<int, array{product_id: int, quantity: float}> $items
     * @return array<int, \Domain\Entities\Product> Arreglo de productos actualizados listos para persistir.
     * @throws DomainException
     */
    public function validateAndReserveStock(array $items): array
    {
        $productsToUpdate = [];

        foreach ($items as $item) {
            $product = $this->productRepository->findById($item['product_id']);

            if (!$product) {
                throw new DomainException("El producto con ID {$item['product_id']} no existe en el catálogo.");
            }

            // Invariantes puras del modelo Product
            $product->assertIsActive();
            $product->assertStockAvailable($item['quantity']);

            // Mutación del estado en memoria
            $product->decreaseStock($item['quantity']);
            $productsToUpdate[] = $product;
        }

        return $productsToUpdate;
    }
}
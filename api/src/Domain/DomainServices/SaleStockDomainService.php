<?php

namespace Domain\DomainServices;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Exceptions\DomainException;
use Domain\ValueObjects\SaleItemInput;

class SaleStockDomainService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Valida existencias y aplica el descuento en memoria para una lista de ítems.
     *
     * @param list<SaleItemInput> $items
     * @return array<int, \Domain\Entities\Product> Arreglo de productos actualizados listos para persistir.
     * @throws DomainException
     */
    public function validateAndReserveStock(array $items): array
    {
        $productsToUpdate = [];

        foreach ($items as $item) {
            $productId = $item->productId;
            $quantity  = $item->quantity;

            $product = $this->productRepository->findById( $productId);

            if (!$product) {
                throw new DomainException("El producto con ID {$productId} no existe en el catálogo.");
            }

            // Invariantes puras del modelo Product
            $product->assertIsActive();
            $product->assertStockAvailable($quantity);

            // Mutación del estado en memoria
            $product->decreaseStock($quantity);
            $productsToUpdate[] = $product;
        }

        return $productsToUpdate;
    }
}
<?php

namespace Modules\Inventory\Services;

use Domain\Contracts\InventoryMovementRepositoryInterface;
use Domain\Contracts\ProductRepositoryInterface;
use Domain\DomainServices\InventoryDomainService;
use Domain\Entities\InventoryMovement;
use Domain\Exceptions\DomainException;
use Infrastructure\Base\BaseService;
use Modules\Inventory\DTOs\InventoryMovement\RegisterMovementDTO;

/**
 * Servicio de Aplicación para la orquestación del Kardex de Inventario.
 */
class KardexService extends BaseService
{
    public function __construct(
        protected InventoryMovementRepositoryInterface $kardexRepository,
        protected ProductRepositoryInterface $productRepository,
        protected InventoryDomainService $inventoryDomainService
    ) {}

    /**
     * Registra una salida transaccional de inventario (ej. Ventas).
     *
     * @param RegisterMovementDTO $dto
     * @return InventoryMovement
     */
    public function registerOutput(RegisterMovementDTO $dto): InventoryMovement
    {
        $product = $this->productRepository->findById($dto->productId);
        
        if (!$product) {
            throw new DomainException("El producto especificado no existe.");
        }

        $movementData = $this->inventoryDomainService->processMovement($product, $dto);

        return $this->kardexRepository->create($movementData);
    }

    /**
     * Registra una entrada transaccional de inventario (ej. Compras).
     *
     * @param RegisterMovementDTO $dto
     * @return InventoryMovement
     */
    public function registerInput(RegisterMovementDTO $dto): InventoryMovement
    {
        $product = $this->productRepository->findById($dto->productId);

        if (!$product) {
            throw new DomainException("El producto especificado no existe.");
        }

        $movementData = $this->inventoryDomainService->processMovement($product, $dto);

        return $this->kardexRepository->create($movementData);
    }
}
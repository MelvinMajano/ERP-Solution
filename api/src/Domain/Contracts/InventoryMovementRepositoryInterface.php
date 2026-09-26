<?php

namespace Domain\Contracts;

use Domain\Entities\InventoryMovement;

/**
 * Contrato de abstracción para la persistencia de movimientos del Kardex.
 */
interface InventoryMovementRepositoryInterface
{
    /**
     * Asienta una nueva entrada o salida física en la bitácora del Kardex.
     *
     * @param array<string, mixed> $data
     * @return InventoryMovement
     */
    public function create(array $data): InventoryMovement;

    /**
     * Obtiene el historial de movimientos de un producto específico.
     *
     * @param int $productId
     * @return array<int, InventoryMovement>
     */
    public function findByProduct(int $productId): array;
}
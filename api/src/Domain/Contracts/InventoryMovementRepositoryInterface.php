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
     */
    public function create(array $data): InventoryMovement;
}
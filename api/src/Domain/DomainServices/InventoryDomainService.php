<?php

namespace Domain\DomainServices;

use Domain\Entities\Product;
use Modules\Inventory\DTOs\RegisterMovementDTO;

/**
 * Servicio de Dominio responsable de las invariantes del Kardex, recálculo de stock y costos.
 */
class InventoryDomainService
{
    /**
     * Aplica la variación física al producto y retorna la estructura previa/posterior para la bitácora.
     *
     * @param Product $product
     * @param RegisterMovementDTO $dto
     * @return array<string, mixed>
     */
    public function processMovement(Product $product, RegisterMovementDTO $dto): array
    {
        $previousStock = $product->current_stock;

        if ($dto->movementType === 'OUT') {
            $product->assertStockAvailable($dto->quantity);
            $newStock = $previousStock - $dto->quantity;
            $product->applyStockDelta(-$dto->quantity);
        } else {
            $newStock = $previousStock + $dto->quantity;
            $product->applyStockDelta($dto->quantity);
        }

        if ($dto->movementType === 'IN' && $dto->unitCost > 0) {
            $product->updateCost($dto->unitCost);
        }

        $product->save();

        return [
            'tenant_id'          => $dto->tenantId,
            'product_id'         => $product->id,
            'movement_reason_id' => $dto->movementReasonId,
            'type'               => $dto->movementType,
            'quantity'           => $dto->quantity,
            'previous_stock'     => $previousStock,
            'new_stock'          => $newStock,
            'unit_cost'          => $dto->unitCost,
            'reference_type'     => $dto->referenceType,
            'reference_id'       => $dto->referenceId,
            'notes'              => $dto->description,
            'created_by'         => $dto->createdBy,
        ];
    }
}
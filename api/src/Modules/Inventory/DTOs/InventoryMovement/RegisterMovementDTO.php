<?php

namespace Modules\Inventory\DTOs;

/**
 * DTO inmutable para mapear la creación de un movimiento de inventario.
 */
readonly class RegisterMovementDTO
{
    public function __construct(
        public int $productId,
        public int $movementReasonId,
        public string $movementType,
        public float $quantity,
        public string $referenceType,
        public ?int $referenceId,
        public ?string $description,
        public float $unitCost,
        public int $createdBy,
        public ?int $tenantId = null
    ) {}

    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            productId: (int) $validatedData['product_id'],
            movementReasonId: (int) $validatedData['movement_reason_id'],
            movementType: strtoupper((string) $validatedData['movement_type']),
            quantity: (float) $validatedData['quantity'],
            referenceType: strtoupper((string) $validatedData['reference_type']),
            referenceId: isset($validatedData['reference_id']) ? (int) $validatedData['reference_id'] : null,
            description: isset($validatedData['description']) ? (string) $validatedData['description'] : null,
            unitCost: (float) $validatedData['unit_cost'],
            createdBy: (int) $validatedData['created_by'],
            tenantId: isset($validatedData['tenant_id']) ? (int) $validatedData['tenant_id'] : null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'tenant_id'          => $this->tenantId,
            'product_id'         => $this->productId,
            'movement_reason_id' => $this->movementReasonId,
            'type'               => $this->movementType,
            'quantity'           => $this->quantity,
            'reference_type'     => $this->referenceType,
            'reference_id'       => $this->referenceId,
            'notes'              => $this->description,
            'unit_cost'          => $this->unitCost,
            'created_by'         => $this->createdBy,
        ], static fn($val) => $val !== null);
    }
}
<?php

declare(strict_types=1);

namespace Modules\POS\DTOs;

readonly class CreateSaleDTO
{
    /**
     * @param array<int, array{product_id: int, quantity: float, unit_price: float, discount: float}> $items
     */
    public function __construct(
        public int $customerId,
        public int $cashierUserId,
        public array $items,
        public ?int $cashBatchId = null,
        public ?string $notes = null,
    ) {}

    /**
     * Crea una instancia del DTO a partir de un array de datos previamente validados.
     *
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            customerId: (int) $validatedData['customer_id'],
            cashierUserId: (int) $validatedData['cashier_user_id'],
            cashBatchId: isset($validatedData['cash_batch_id']) ? (int) $validatedData['cash_batch_id'] : null,
            notes: isset($validatedData['notes']) ? (string) $validatedData['notes'] : null,
            items: array_map(static fn (array $item) => [
                'product_id' => (int) $item['product_id'],
                'quantity'   => (float) $item['quantity'],
                'unit_price' => (float) $item['unit_price'],
                'discount'   => isset($item['discount']) ? (float) $item['discount'] : 0.0,
            ], $validatedData['items'] ?? [])
        );
    }

    /**
     * Convierte el DTO en un array asociativo excluyendo los valores nulos.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'customer_id'     => $this->customerId,
            'cashier_user_id' => $this->cashierUserId,
            'cash_batch_id'   => $this->cashBatchId,
            'notes'           => $this->notes,
            'items'           => $this->items,
        ], static fn($val) => $val !== null);
    }
}
<?php

namespace Modules\Cash\DTOs\CashBatchDtos;

readonly class CloseCashBatchDTO
{
    public function __construct(
        public int $id,
        public float $actualAmount
    ) {}

    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            id: (int) $validatedData['id'],
            actualAmount: (float) $validatedData['actual_amount']
        );
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'actual_amount' => $this->actualAmount,
        ];
    }
}
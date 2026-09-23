<?php

namespace Modules\Cash\DTOs;

readonly class OpenCashBatchDTO
{
    public function __construct(
        public int $cashierUserId,
        public float $openingBalance = 0.00,
        public ?int $createdBy = null
    ) {}

    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            cashierUserId: (int) $validatedData['cashier_user_id'],
            openingBalance: (float) ($validatedData['opening_balance'] ?? 0.00),
            createdBy: isset($validatedData['created_by']) ? (int) $validatedData['created_by'] : null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'cashier_user_id' => $this->cashierUserId,
            'opening_balance' => $this->openingBalance,
            'created_by'      => $this->createdBy,
        ], static fn($val) => $val !== null);
    }
}
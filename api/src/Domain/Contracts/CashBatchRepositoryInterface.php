<?php

namespace Domain\Contracts;

use Domain\Entities\CashBatch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CashBatchRepositoryInterface
{
    public function findById(int|string $id): ?CashBatch;

    public function findActiveByUserId(int $userId): ?CashBatch;

    public function all(array $params = []): LengthAwarePaginator;

    public function create(array $data): CashBatch;

    public function update(int|string $id, array $data): bool;

    public function setStatus(int|string $id, bool $isActive): bool;
}
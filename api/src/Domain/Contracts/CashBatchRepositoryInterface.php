<?php

declare(strict_types=1);

namespace Domain\Contracts;

use Domain\Entities\CashBatch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CashBatchRepositoryInterface
{
    public function findById(int|string $id): ?CashBatch;

    /**
     * Busca el turno de caja activo para un cajero específico.
     */
    public function findActiveByCashierId(int $cashierUserId): ?CashBatch;

    public function all(array $params = []): LengthAwarePaginator;

    public function create(array $data): CashBatch;

    public function update(int|string $id, array $data): bool;

    public function setStatus(int|string $id, bool $isActive): bool;
}
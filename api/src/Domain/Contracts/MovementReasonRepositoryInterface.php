<?php

namespace Domain\Contracts;

use Domain\Entities\MovementReason;
use Infrastructure\Contracts\RepositoryInterface;

interface MovementReasonRepositoryInterface extends RepositoryInterface
{
    public function findById(int|string $id): ?MovementReason;

    public function findByName(string $name, int|string|null $ignoreId = null): ?MovementReason;

    public function create(array $data): MovementReason;
}
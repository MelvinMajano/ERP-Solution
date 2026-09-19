<?php

namespace Modules\Inventory\Repositories;

use Domain\Contracts\MovementReasonRepositoryInterface;
use Domain\Entities\MovementReason;
use Infrastructure\Base\BaseRepository;

class MovementReasonRepository extends BaseRepository implements MovementReasonRepositoryInterface
{
    protected array $sortableColumns = ['id', 'name', 'movement_type', 'is_active', 'created_at'];

    protected array $likeColumns = ['name'];

    public function __construct(MovementReason $model)
    {
        parent::__construct($model);
    }

    public function findById(int|string $id): ?MovementReason
    {
        /** @var MovementReason|null */
        return parent::findById($id);
    }

    public function findByName(string $name, int|string|null $ignoreId = null): ?MovementReason
    {
        /** @var MovementReason|null */
        return $this->query()
            ->where('name', $name)
            ->when($ignoreId, static fn($query) => $query->where('id', '!=', $ignoreId))
            ->first();
    }

    public function create(array $data): MovementReason
    {
        /** @var MovementReason */
        return parent::create($data);
    }
}
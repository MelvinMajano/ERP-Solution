<?php

namespace Modules\Cash\Repositories;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\Entities\CashBatch;
use Infrastructure\Base\BaseRepository;

class CashBatchRepository extends BaseRepository implements CashBatchRepositoryInterface
{
    protected array $sortableColumns = [
        'id',
        'user_id',
        'opening_balance',
        'closing_balance',
        'status',
        'opened_at',
        'closed_at'
    ];

    protected array $likeColumns = ['notes'];

    public function __construct(CashBatch $model)
    {
        parent::__construct($model);
    }

    public function findById(int|string $id): ?CashBatch
    {
        /** @var CashBatch|null */
        return parent::findById($id);
    }

    public function findActiveByUserId(int $userId): ?CashBatch
    {
        /** @var CashBatch|null */
        return $this->query()
            ->where('user_id', $userId)
            ->where('status', 'open')
            ->first();
    }

    public function create(array $data): CashBatch
    {
        /** @var CashBatch */
        return parent::create($data);
    }
}
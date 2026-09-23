<?php

namespace Modules\Cash\Repositories;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\Entities\CashBatch;
use Infrastructure\Base\BaseRepository;

class CashBatchRepository extends BaseRepository implements CashBatchRepositoryInterface
{
    protected array $sortableColumns = [
        'id',
        'cashier_user_id',
        'opening_balance',
        'expected_amount',
        'actual_amount',
        'difference',
        'total_sales',
        'status',
        'opening_date',
        'closing_date',
    ];

    public function __construct(CashBatch $model)
    {
        parent::__construct($model);
    }

    public function findById(int|string $id): ?CashBatch
    {
        /** @var CashBatch|null */
        return parent::findById($id);
    }

    public function findActiveByCashierId(int $cashierUserId): ?CashBatch
    {
        /** @var CashBatch|null */
        return $this->query()
            ->where('cashier_user_id', $cashierUserId)
            ->where('status', 'OPEN')
            ->first();
    }

    public function create(array $data): CashBatch
    {
        /** @var CashBatch */
        return parent::create($data);
    }
}
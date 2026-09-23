<?php

namespace Modules\Cash\Transformers;

use Domain\Entities\CashBatch;
use Infrastructure\Base\BaseTransformer;

class CashBatchTransformer extends BaseTransformer
{
    public function transform(mixed $item): array
    {
        /** @var CashBatch $item */
        return [
            'id'             => $item->id,
            'cashierUserId'  => $item->cashier_user_id,
            'openingBalance' => (float) $item->opening_balance,
            'expectedAmount' => (float) $item->expected_amount,
            'actualAmount'   => $item->actual_amount !== null ? (float) $item->actual_amount : null,
            'difference'     => $item->difference !== null ? (float) $item->difference : null,
            'totalSales'     => (float) $item->total_sales,
            'status'         => $item->status,
            'openingDate'    => $item->opening_date?->format('Y-m-d H:i:s'),
            'closingDate'    => $item->closing_date?->format('Y-m-d H:i:s'),
            'createdBy'      => $item->created_by,
        ];
    }
}
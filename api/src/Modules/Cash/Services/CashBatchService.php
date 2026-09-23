<?php

namespace Modules\Cash\Services;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\DomainServices\CashBatchDomainService;
use Domain\Entities\CashBatch;
use Domain\Exceptions\DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseService;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Modules\Cash\DTOs\CloseCashBatchDTO;
use Modules\Cash\DTOs\OpenCashBatchDTO;
use Carbon\Carbon;

class CashBatchService extends BaseService
{
    public function __construct(
        private readonly CashBatchRepositoryInterface $cashBatchRepository,
        private readonly CashBatchDomainService $cashBatchDomainService
    ) {}

    public function get(PaginationQueryDTO $dto): LengthAwarePaginator
    {
        return $this->cashBatchRepository->all($dto->toArray());
    }

    public function getById(EntityIdDTO $dto): CashBatch
    {
        return $this->cashBatchRepository->findById($dto->id) 
            ?? throw new DomainException("El turno de caja solicitado no existe.");
    }

    public function openBatch(OpenCashBatchDTO $dto): CashBatch
    {
        $this->cashBatchDomainService->validateCashierHasNoActiveBatch($dto->cashierUserId);

        $payload = array_merge($dto->toArray(), [
            'status'          => 'OPEN',
            'opened_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'expected_amount' => $dto->openingBalance,
        ]);

        return $this->cashBatchRepository->create($payload);
    }

    public function closeBatch(CloseCashBatchDTO $dto): CashBatch
    {
        $batch = $this->getById(new EntityIdDTO($dto->id));

        $this->cashBatchDomainService->validateCanClose($batch);

        $difference = $dto->actualAmount - $batch->expected_amount;

        $updateData = [
            'actual_amount' => $dto->actualAmount,
            'difference'    => $difference,
            'status'        => 'CLOSED',
            'closing_date'  => (new \DateTime())->format('Y-m-d H:i:s'),
        ];

        $this->cashBatchRepository->update($dto->id, $updateData);

        return $this->getById(new EntityIdDTO($dto->id));
    }
}
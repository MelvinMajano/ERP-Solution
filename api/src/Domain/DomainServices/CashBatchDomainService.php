<?php

namespace Domain\DomainServices;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\Entities\CashBatch;
use Domain\Exceptions\DomainException;

class CashBatchDomainService
{
    public function __construct(
        private readonly CashBatchRepositoryInterface $cashBatchRepository
    ) {}

    /**
     * Garantiza que el cajero no tenga un turno activo.
     *
     * @throws DomainException
     */
    public function validateCashierHasNoActiveBatch(int $cashierUserId): void
    {
        $activeBatch = $this->cashBatchRepository->findActiveByCashierId($cashierUserId);

        if ($activeBatch) {
            throw new DomainException("El cajero ya posee un turno de caja abierto (ID: {$activeBatch->id}).");
        }
    }

    /**
     * Evalúa las condiciones requeridas para cerrar la caja.
     *
     * @throws DomainException
     */
    public function validateCanClose(CashBatch $cashBatch): void
    {
        $cashBatch->assertIsOpen();
    }
}
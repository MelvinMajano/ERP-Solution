<?php

namespace Domain\DomainServices;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\Exceptions\DomainException;

/**
 * Mantiene y ejecuta las reglas de negocio globales e invariantes de caja.
 */
class CashBatchDomainService
{
    public function __construct(
        private readonly CashBatchRepositoryInterface $cashBatchRepository
    ) {}

    /**
     * Valida que un usuario no tenga una caja abierta actualmente.
     *
     * @throws DomainException
     */
    public function validateUserHasNoActiveBatch(int $userId): void
    {
        $activeBatch = $this->cashBatchRepository->findActiveByUserId($userId);

        if ($activeBatch) {
            throw new DomainException("El usuario ya tiene una caja abierta con ID {$activeBatch->id}.");
        }
    }
}
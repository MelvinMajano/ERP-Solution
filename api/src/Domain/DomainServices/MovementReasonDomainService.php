<?php

namespace Domain\DomainServices;

use Domain\Contracts\MovementReasonRepositoryInterface;
use Domain\Entities\MovementReason;
use Domain\Exceptions\DomainException;

class MovementReasonDomainService
{
    public function __construct(
        private readonly MovementReasonRepositoryInterface $movementReasonRepository
    ) {}

    /**
     * Valida la unicidad del nombre del motivo dentro del tenant actual.
     *
     * @throws DomainException
     */
    public function validateUniqueName(string $name, int|string|null $ignoreId = null): void
    {
        $existing = $this->movementReasonRepository->findByName($name, $ignoreId);

        if ($existing) {
            throw new DomainException("El motivo de movimiento '{$name}' ya se encuentra registrado.");
        }
    }

    /**
     * Verifica si la entidad de dominio está habilitada para usarse.
     *
     * @throws DomainException
     */
    public function validateCanOperate(MovementReason $reason): void
    {
        $reason->assertIsActive();
    }
}
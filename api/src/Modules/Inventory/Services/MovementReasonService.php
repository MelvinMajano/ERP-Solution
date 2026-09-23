<?php

namespace Modules\Inventory\Services;

use Domain\Contracts\MovementReasonRepositoryInterface;
use Domain\DomainServices\MovementReasonDomainService;
use Domain\Entities\MovementReason;
use Domain\Exceptions\DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseService;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Infrastructure\DTOs\SetStatusDTO;
use Modules\Inventory\DTOs\MovementReasonDtos\CreateMovementReasonDTO;
use Modules\Inventory\DTOs\MovementReasonDtos\UpdateMovementReasonDTO;

/**
 * Servicio de Aplicación para los Motivos de Movimiento.
 *
 * Actúa como orquestador puro entre la capa de entrada (Controladores), el Servicio de Dominio
 * y la Infraestructura (Repositorios). No contiene lógica directa de reglas de negocio if/else.
 */
class MovementReasonService extends BaseService
{
    /**
     * Inicializa el servicio de aplicación con sus dependencias.
     *
     * @param MovementReasonRepositoryInterface $movementReasonRepository Contrato de acceso a datos.
     * @param MovementReasonDomainService $movementReasonDomainService Servicio de dominio para reglas de negocio.
     */
    public function __construct(
        private readonly MovementReasonRepositoryInterface $movementReasonRepository,
        private readonly MovementReasonDomainService $movementReasonDomainService
    ) {}

    /**
     * Obtiene una lista paginada y filtrada de motivos de movimiento.
     *
     * @param PaginationQueryDTO $dto DTO con parámetros de paginación, filtros y orden.
     * @return LengthAwarePaginator Colección paginada de motivos.
     */
    public function get(PaginationQueryDTO $dto): LengthAwarePaginator
    {
        return $this->movementReasonRepository->all($dto->toArray());
    }

    /**
     * Recupera un motivo de movimiento específico por su ID.
     *
     * @param EntityIdDTO $dto DTO conteniendo el ID a consultar.
     * @return MovementReason Entidad del motivo solicitada.
     * @throws DomainException Si el motivo no existe.
     */
    public function getById(EntityIdDTO $dto): MovementReason
    {
        return $this->movementReasonRepository->findById($dto->id) 
            ?? throw new DomainException("El motivo de movimiento solicitado no existe.");
    }

    /**
     * Orquesta la creación de un nuevo motivo de movimiento.
     *
     * @param CreateMovementReasonDTO $dto Datos estructurados para la creación.
     * @return MovementReason Instancia recién creada.
     * @throws DomainException Si el nombre del motivo ya se encuentra duplicado.
     */
    public function createReason(CreateMovementReasonDTO $dto): MovementReason
    {
        $this->movementReasonDomainService->validateUniqueName($dto->name);

        return $this->movementReasonRepository->create($dto->toArray());
    }

    /**
     * Orquesta la actualización parcial o total de un motivo existente.
     *
     * @param UpdateMovementReasonDTO $dto Datos validados para la actualización.
     * @return MovementReason Entidad actualizada con los datos refrescados de la BD.
     * @throws DomainException Si el registro no existe o el nuevo nombre choca con otro existente.
     */
    public function updateReason(UpdateMovementReasonDTO $dto): MovementReason
    {
        $reason = $this->getById(new EntityIdDTO($dto->id));

        if ($dto->name !== null && $dto->name !== $reason->name) {
            $this->movementReasonDomainService->validateUniqueName($dto->name, $dto->id);
        }

        $this->movementReasonRepository->update($dto->id, $dto->toArray());

        return $this->getById(new EntityIdDTO($dto->id));
    }

    /**
     * Cambia el estado (habilitado/inhabilitado) de un motivo de movimiento.
     *
     * @param SetStatusDTO $dto DTO con el ID del registro y el nuevo estado booleano.
     * @return MovementReason Entidad con el estado actualizado.
     * @throws DomainException Si el registro no existe.
     */
    public function setStatus(SetStatusDTO $dto): MovementReason
    {
        $this->getById(new EntityIdDTO($dto->id));
        $this->movementReasonRepository->setStatus($dto->id, $dto->isActive);

        return $this->getById(new EntityIdDTO($dto->id));
    }
}
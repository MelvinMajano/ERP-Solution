<?php

namespace Modules\Billing\Services;

use Domain\Contracts\CustomerRepositoryInterface;
use Domain\Entities\Customer;
use Domain\Exceptions\DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseService;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Infrastructure\DTOs\SetStatusDTO;
use Modules\Billing\DTOs\CustomersDtos\CreateCustomerDTO;
use Modules\Billing\DTOs\CustomersDtos\UpdateCustomerDTO;

/**
 * Servicio de Aplicación para la orquestación de casos de uso de Clientes.
 */
class CustomerService extends BaseService
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * Consulta el catálogo paginado de clientes aplicando filtros dinámicos.
     *
     * @return LengthAwarePaginator<Customer>
     */
    public function get(PaginationQueryDTO $dto): LengthAwarePaginator
    {
        return $this->customerRepository->all($dto->toArray());
    }

    /**
     * Obtiene una entidad de cliente por su llave primaria en el contexto del tenant activo.
     *
     * @throws DomainException Si el cliente no existe o no pertenece al tenant actual.
     */
    public function getById(EntityIdDTO $dto): Customer
    {
        $customer = $this->customerRepository->findById($dto->id);

        if (!$customer) {
            throw new DomainException("El cliente solicitado no existe.");
        }

        return $customer;
    }

    /**
     * Registra un nuevo cliente en el sistema.
     */
    public function createCustomer(CreateCustomerDTO $dto): Customer
    {
        return $this->customerRepository->create($dto->toArray());
    }

    /**
     * Modifica los datos de un cliente existente de forma parcial o completa.
     *
     * @throws DomainException Si el ID del cliente a actualizar no existe.
     */
    public function updateCustomer(UpdateCustomerDTO $dto): Customer
    {
        $this->getById(new EntityIdDTO($dto->id));
        $this->customerRepository->update($dto->id, $dto->toArray());

        return $this->getById(new EntityIdDTO($dto->id));
    }

    /**
     * Establece la disponibilidad comercial de un cliente (desactivación/activación).
     *
     * @throws DomainException Si el cliente no existe.
     */
    public function setStatus(SetStatusDTO $dto): Customer
    {
        $this->getById(new EntityIdDTO($dto->id));
        $this->customerRepository->setStatus($dto->id, $dto->isActive);

        return $this->getById(new EntityIdDTO($dto->id));
    }

    /**
     * Elimina físicamente un registro de cliente previa comprobación de existencia.
     *
     * @throws DomainException
     */
    public function deleteCustomer(EntityIdDTO $dto): bool
    {
        $this->getById(new EntityIdDTO($dto->id));
        return $this->customerRepository->delete($dto->id);
    }
}
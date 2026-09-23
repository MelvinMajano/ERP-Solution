<?php

namespace Modules\Billing\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Infrastructure\Base\BaseController;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Infrastructure\DTOs\SetStatusDTO;
use Modules\Billing\DTOs\CustomersDtos\CreateCustomerDTO;
use Modules\Billing\DTOs\CustomersDtos\UpdateCustomerDTO;
use Modules\Billing\Services\CustomerService;
use Modules\Billing\Transformers\CustomerTransformer;
use Modules\Billing\Validators\CustomerValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controlador de Puntos de Entrada HTTP para el recurso de Clientes.
 */
class CustomerController extends BaseController
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly CustomerTransformer $customerTransformer
    ) {}

    /**
     * Maneja el listado de clientes paginados.
     *
     * GET /api/v1/customers
     */
    public function get(Request $request, Response $response): Response
    {
        $validatedData = CustomerValidator::validatePagination($request->getQueryParams());
        $filterDto = PaginationQueryDTO::fromValidatedData($validatedData);

        return $this->paginatedResponse(
            response: $response,
            paginator: $this->customerService->get($filterDto),
            transformer: $this->customerTransformer,
            message: 'Clientes obtenidos con éxito'
        );
    }

    /**
     * Obtiene los detalles de un cliente específico por ID.
     *
     * GET /api/v1/customers/{id}
     */
    public function getById(Request $request, Response $response, array $args): Response
    {
        $validatedData = CustomerValidator::validateId((int) $args['id']);
        $dto = EntityIdDTO::fromValidatedData($validatedData);

        $customer = $this->customerService->getById($dto);

        return $this->jsonResponse(
            response: $response,
            data: $this->customerTransformer->transform($customer),
            message: 'Cliente obtenido con éxito'
        );
    }

    /**
     * Procesa la creación de un nuevo cliente.
     *
     * POST /api/v1/customers
     */
    public function create(Request $request, Response $response): Response
    {
        $validatedData = CustomerValidator::createValidation((array) $request->getParsedBody());
        // Inyectar el user_id de la sesión HTTP al arreglo
        $userId = (int) $request->getAttribute('user_id');
        $validatedData['created_by'] = $userId;
        $createDto = CreateCustomerDTO::fromValidatedData($validatedData);

        $customer = $this->customerService->createCustomer($createDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->customerTransformer->transform($customer),
            message: 'Cliente creado con éxito',
            statusCode: StatusCodeInterface::STATUS_CREATED
        );
    }

    /**
     * Procesa la modificación de datos de un cliente existente.
     *
     * PUT /api/v1/customers/{id}
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $validatedData = CustomerValidator::updateValidation((int) $args['id'], (array) $request->getParsedBody());
        $updateDto = UpdateCustomerDTO::fromValidatedData($validatedData);

        $customer = $this->customerService->updateCustomer($updateDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->customerTransformer->transform($customer),
            message: 'Cliente actualizado con éxito'
        );
    }

    /**
     * Procesa la actualización del estado (activo/inactivo) de un cliente.
     *
     * PATCH /api/v1/customers/{id}/status
     */
    public function setStatus(Request $request, Response $response, array $args): Response
    {
        $validatedData = CustomerValidator::validateStatus((int) $args['id'], (array) $request->getParsedBody());
        $setStatusDto = SetStatusDTO::fromValidatedData($validatedData);

        $customer = $this->customerService->setStatus($setStatusDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->customerTransformer->transform($customer),
            message: 'Estado del cliente actualizado correctamente'
        );
    }

    /**
     * Procesa la eliminación física de un registro de cliente.
     *
     * DELETE /api/v1/customers/{id}
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $validatedData = CustomerValidator::validateId((int) $args['id']);
        $deleteDto = EntityIdDTO::fromValidatedData($validatedData);

        $this->customerService->deleteCustomer($deleteDto);

        return $this->jsonResponse(
            response: $response,
            data: null,
            message: 'Cliente eliminado con éxito'
        );
    }
}
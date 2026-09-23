<?php

namespace Modules\Inventory\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Infrastructure\Base\BaseController;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Infrastructure\DTOs\SetStatusDTO;
use Modules\Inventory\DTOs\MovementReasonDtos\CreateMovementReasonDTO;
use Modules\Inventory\DTOs\MovementReasonDtos\UpdateMovementReasonDTO;
use Modules\Inventory\Services\MovementReasonService;
use Modules\Inventory\Transformers\MovementReasonTransformer;
use Modules\Inventory\Validators\MovementReasonValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controlador de Endpoints HTTP para el recurso MovementReason.
 *
 * Punto de entrada REST que procesa las peticiones Slim, orquesta la validación,
 * llama a la capa de aplicación y construye las respuestas HTTP estandarizadas.
 */
class MovementReasonController extends BaseController
{
    /**
     * @param MovementReasonService $movementReasonService Servicio de aplicación.
     * @param MovementReasonTransformer $movementReasonTransformer Transformador para la salida JSON.
     */
    public function __construct(
        private readonly MovementReasonService $movementReasonService,
        private readonly MovementReasonTransformer $movementReasonTransformer
    ) {}

    /**
     * Endpoint GET /movement-reasons
     * Retorna el listado paginado de motivos de movimiento.
     *
     * @param Request $request Objeto de petición de Slim.
     * @param Response $response Objeto de respuesta de Slim.
     * @return Response Respuesta HTTP JSON paginada.
     */
    public function get(Request $request, Response $response): Response
    {
        $validatedData = MovementReasonValidator::validatePagination($request->getQueryParams());
        $dto = PaginationQueryDTO::fromValidatedData($validatedData);

        $paginatedData = $this->movementReasonService->get($dto);

        return $this->paginatedResponse(
            response: $response,
            paginator: $paginatedData,
            transformer: $this->movementReasonTransformer,
            message: 'Motivos de movimiento obtenidos con éxito'
        );
    }

    /**
     * Endpoint GET /movement-reasons/{id}
     * Obtiene un detalle específico por ID.
     *
     * @param Request $request
     * @param Response $response
     * @param array<string, string> $args Argumentos pasados por la URL (ej: id).
     * @return Response Respuesta HTTP JSON con el motivo encontrado.
     */
    public function getById(Request $request, Response $response, array $args): Response
    {
        $validatedData = MovementReasonValidator::validateId((int) $args['id']);
        $dto = EntityIdDTO::fromValidatedData($validatedData);

        $reason = $this->movementReasonService->getById($dto);

        return $this->jsonResponse(
            response: $response,
            data: $this->movementReasonTransformer->transform($reason),
            message: 'Motivo de movimiento obtenido con éxito'
        );
    }

    /**
     * Endpoint POST /movement-reasons
     * Registra un nuevo motivo de movimiento.
     *
     * @param Request $request
     * @param Response $response
     * @return Response Respuesta HTTP con status 201 CREATED.
     */
    public function create(Request $request, Response $response): Response
    {
        $body = (array) $request->getParsedBody();
        $validatedData = MovementReasonValidator::createValidation($body);

        $createDto = CreateMovementReasonDTO::fromValidatedData($validatedData);
        $reason = $this->movementReasonService->createReason($createDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->movementReasonTransformer->transform($reason),
            message: 'Motivo de movimiento creado con éxito',
            statusCode: StatusCodeInterface::STATUS_CREATED
        );
    }

    /**
     * Endpoint PUT /movement-reasons/{id}
     * Actualiza los datos de un motivo de movimiento existente.
     *
     * @param Request $request
     * @param Response $response
     * @param array<string, string> $args
     * @return Response Respuesta HTTP JSON con los datos actualizados.
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $body = (array) $request->getParsedBody();

        $validatedData = MovementReasonValidator::updateValidation($id, $body);
        $updateDto = UpdateMovementReasonDTO::fromValidatedData($validatedData);

        $reason = $this->movementReasonService->updateReason($updateDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->movementReasonTransformer->transform($reason),
            message: 'Motivo de movimiento actualizado con éxito'
        );
    }

    /**
     * Endpoint PATCH /movement-reasons/{id}/status
     * Habilita o inhabilita un motivo de movimiento.
     *
     * @param Request $request
     * @param Response $response
     * @param array<string, string> $args
     * @return Response
     */
    public function setStatus(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $body = (array) $request->getParsedBody();

        $validatedData = MovementReasonValidator::validateStatus($id, $body);
        $setStatusDto = SetStatusDTO::fromValidatedData($validatedData);

        $reason = $this->movementReasonService->setStatus($setStatusDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->movementReasonTransformer->transform($reason),
            message: 'Estado del motivo actualizado correctamente'
        );
    }
}
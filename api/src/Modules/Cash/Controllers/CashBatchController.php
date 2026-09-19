<?php

namespace Modules\Cash\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Infrastructure\Base\BaseController;
use Infrastructure\DTOs\EntityIdDTO;
use Infrastructure\DTOs\PaginationQueryDTO;
use Modules\Cash\DTOs\CashBatchDtos\CloseCashBatchDTO;
use Modules\Cash\DTOs\CashBatchDtos\OpenCashBatchDTO;
use Modules\Cash\Services\CashBatchService;
use Modules\Cash\Transformers\CashBatchTransformer;
use Modules\Cash\Validators\CashBatchValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CashBatchController extends BaseController
{
    public function __construct(
        private readonly CashBatchService $cashBatchService,
        private readonly CashBatchTransformer $cashBatchTransformer
    ) {}

    /**
     * Consulta el historial paginado de turnos de caja.
     * GET /cash-batches
     */
    public function get(Request $request, Response $response): Response
    {
        $validatedData = CashBatchValidator::validatePagination($request->getQueryParams());
        $filterDto = PaginationQueryDTO::fromValidatedData($validatedData);

        $paginatedData = $this->cashBatchService->get($filterDto);

        return $this->paginatedResponse(
            response: $response,
            paginator: $paginatedData,
            transformer: $this->cashBatchTransformer,
            message: 'Turnos de caja obtenidos con éxito'
        );
    }

    /**
     * Obtiene los detalles de un turno de caja específico.
     * GET /cash-batches/{id}
     */
    public function getById(Request $request, Response $response, array $args): Response
    {
        $validatedData = CashBatchValidator::validateId((int) $args['id']);
        $dto = EntityIdDTO::fromValidatedData($validatedData);

        $batch = $this->cashBatchService->getById($dto);

        return $this->jsonResponse(
            response: $response,
            data: $this->cashBatchTransformer->transform($batch),
            message: 'Turno de caja obtenido con éxito'
        );
    }

    /**
     * Apertura un nuevo turno de caja para un cajero.
     * POST /cash-batches/open
     */
    public function open(Request $request, Response $response): Response
    {
        $body = (array) $request->getParsedBody();
        $validatedData = CashBatchValidator::openValidation($body);

        // Se inyecta el id del usuario que realiza la petición (extraído por middleware JWT)
        $validatedData['created_by'] = (int) $request->getAttribute('user_id');

        $openDto = OpenCashBatchDTO::fromValidatedData($validatedData);
        $batch = $this->cashBatchService->openBatch($openDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->cashBatchTransformer->transform($batch),
            message: 'Turno de caja abierto correctamente',
            statusCode: StatusCodeInterface::STATUS_CREATED
        );
    }

    /**
     * Cierra el turno de caja evaluando el monto físico reportado.
     * POST /cash-batches/{id}/close
     */
    public function close(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $body = (array) $request->getParsedBody();

        $validatedData = CashBatchValidator::closeValidation($id, $body);
        $closeDto = CloseCashBatchDTO::fromValidatedData($validatedData);

        $batch = $this->cashBatchService->closeBatch($closeDto);

        return $this->jsonResponse(
            response: $response,
            data: $this->cashBatchTransformer->transform($batch),
            message: 'Turno de caja cerrado con éxito'
        );
    }
}
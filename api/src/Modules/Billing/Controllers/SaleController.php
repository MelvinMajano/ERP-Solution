<?php

namespace Modules\Billing\Controllers;

use Modules\Billing\DTOs\CreateSaleDTO;
use Modules\Billing\Services\CreateSaleService;
use Modules\Billing\Transformers\SaleTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controlador HTTP encargado de gestionar la creación de facturas de venta.
 */
class SaleController
{
    public function __construct(
        private readonly CreateSaleService $createSaleService,
        private readonly SaleTransformer $saleTransformer
    ) {}

    /**
     * Procesa la solicitud HTTP para crear una nueva venta/factura.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function store(Request $request, Response $response): Response
    {
        /** @var array<string, mixed> $body */
        $body = (array) $request->getParsedBody();

        // Extraer el usuario autenticado desde los atributos del request (o fallback del payload)
        $cashierUserId = (int) ($request->getAttribute('user_id') ?? $body['cashier_user_id'] ?? 0);

        // Inyectar o asegurar el cajero emisor en el arreglo de datos validados
        $body['cashier_user_id'] = $cashierUserId;

        // Construir el DTO utilizando el método de fábrica con Value Objects
        $dto = CreateSaleDTO::fromValidatedData($body);

        // Ejecutar el servicio de dominio / transacción
        $invoice = $this->createSaleService->execute($dto);

        // Formatear la respuesta con el Transformer
        $payload = [
            'success' => true,
            'message' => 'Venta creada y facturada exitosamente.',
            'data'    => $this->saleTransformer->transform($invoice),
        ];

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
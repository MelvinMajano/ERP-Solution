<?php

namespace Modules\Core\Controllers;

use Infrastructure\Base\BaseController;
use Modules\Core\DTOs\RegisterTenantDTO;
use Modules\Core\Services\OnboardingService;
use Modules\Core\Transformers\TenantTransformer;
use Modules\Core\Validators\OnboardingValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controlador HTTP invocable para el registro inicial de un Tenant.
 */
class OnboardingController extends BaseController
{
    public function __construct(
        private readonly OnboardingService $onboardingService,
        private readonly TenantTransformer $tenantTransformer
    ) {}

    /**
     * Procesa la solicitud POST para dar de alta una nueva empresa y su usuario root.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        //optienen la data del body del request
        $body = (array) $request->getParsedBody();

        //valida que los datos sean correctos
        $validatedData = OnboardingValidator::registerValidation($body);

        // Genera el dto
        $dto = RegisterTenantDTO::fromArray($validatedData);

        //Registra el tenant y el admin
        $tenant = $this->onboardingService->registerTenant($dto);

        // 5. Formatear la entidad usando el Transformer y retornar mediante jsonResponse()
        return $this->jsonResponse(
            response: $response,
            data: ['tenant' => $this->tenantTransformer->transform($tenant)],
            message: 'Empresa y usuario administrador registrados exitosamente.',
            statusCode: 201
        );
    }
}
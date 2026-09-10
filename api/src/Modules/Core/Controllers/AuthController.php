<?php

namespace Modules\Core\Controllers;

use Infrastructure\Base\BaseController;
use Modules\Core\DTOs\LoginDTO;
use Modules\Core\Services\AuthService;
use Modules\Core\Validators\AuthValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Paso 1: Validar correo electrónico y obtener empresas asociadas.
     */
    public function checkEmail(Request $request, Response $response): Response
    {
        $body = (array) $request->getParsedBody();
        $validated = AuthValidator::checkEmailValidation($body);

        $dto = LoginDTO::fromCheckEmail($validated);
        $result = $this->authService->checkEmail($dto);

        return $this->jsonResponse(
            response: $response,
            data: $result,
            message: 'Correo verificado correctamente.',
            statusCode: 200
        );
    }

    /**
     * Paso 2: Validar contraseña y emitir el token de sesión.
     */
    public function loginPassword(Request $request, Response $response): Response
    {
        $body = (array) $request->getParsedBody();
        $validated = AuthValidator::loginPasswordValidation($body);

        $dto = LoginDTO::fromPassword($validated);

        // Obtener el user_id inyectado por PreAuthMiddleware
        $userId = (int) $request->getAttribute('user_id');

        $result = $this->authService->loginPassword($userId, $dto);

        return $this->jsonResponse(
            response: $response,
            data: $result,
            message: 'Autenticación exitosa.',
            statusCode: 200
        );
    }
}
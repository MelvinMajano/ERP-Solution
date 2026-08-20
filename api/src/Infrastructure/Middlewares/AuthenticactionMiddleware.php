<?php

namespace Infrastructure\Middlewares;

use Fig\Http\Message\StatusCodeInterface;
use Modules\Core\Services\AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthenticactionMiddleware implements MiddlewareInterface
{
    public function __construct(private AuthService $authService) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine("Authorization");

        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return $this->buildErrorResponse('token de authenticacion requerido', StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        $token = $matches[1];
        $decoded = $this->authService->checkToken($token);

        if(!$decoded){
            return $this->buildErrorResponse('Token inválido o expirado', StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        if (!isset($decoded->type) || $decoded->type !== 'session_token') {
            return $this->buildErrorResponse('Acceso no autorizado. Debe completar la autenticación en dos pasos', StatusCodeInterface::STATUS_FORBIDDEN);
        }

        $request=$request
            ->withAttribute('user_id',$decoded->sub)
            ->withAttribute('tenant_id',$decoded->tenant_id ?? null)
            ->withAttribute('user_role',$decoded->role ?? null)
            ->withAttribute('token_payload',(array) $decoded);
        return $handler->handle($request);
    }
}

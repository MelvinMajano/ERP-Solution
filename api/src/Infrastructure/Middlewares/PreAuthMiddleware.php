<?php

namespace Infrastructure\Middlewares;

use Infrastructure\Exceptions\UnauthorizedException;
use Modules\Core\Services\AuthService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PreAuthMiddleware implements MiddlewareInterface
{
    public function __construct(private AuthService $authService){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine("Authorization");

        //Valida si existe el token temporal
        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            throw new UnauthorizedException(
                'Token temporal de pre-autenticación requerido', 
                ['pre_auth' => 'token_required']
            );
        }

        //decodifica eltoken
        $token = $matches[1];
        $decoded = $this->authService->checkToken($token);

        //Valida que el token sea válido y de tipo pre_auth_token
        if (!$decoded || !isset($decoded->type) || $decoded->type !== 'pre_auth_token') {
            throw new UnauthorizedException(
                'Token temporal inválido o expirado. Inicie sesión desde el correo electrónico', 
                ['pre_auth' => 'invalid_or_expired']
            );
        }

        //envia la data que se necesita en la api
        $request = $request
            ->withAttribute('user_id', $decoded->sub)
            ->withAttribute('available_tenants', $decoded->tenants ?? [])
            ->withAttribute('pre_auth_payload', (array) $decoded);

        return $handler->handle($request);
    }
}
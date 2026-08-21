<?php

namespace Infrastructure\Middlewares;

use Infrastructure\Exceptions\ForbiddenException;
use Infrastructure\Exceptions\UnauthorizedException;
use Modules\Core\Services\AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticactionMiddleware implements MiddlewareInterface
{
    public function __construct(private AuthService $authService) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine("Authorization");
        // Valida si viene el token en el encabezado
        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            throw new UnauthorizedException('Token de autenticacion requerido',['token'=>'missing']);
        }

        //Decodifica el token
        $token = $matches[1];
        $decoded = $this->authService->checkToken($token);

        //Valida si el token es valido
        if(!$decoded){
            throw new UnauthorizedException('Token invalido o expirado',['token' => 'invalid_or_expired']);
        }

        //Valida que el token no sea un token temporal
        if (!isset($decoded->type) || $decoded->type !== 'session_token') {
            throw new ForbiddenException(
                'Acceso no autorizado. Debe completar la validación de contraseña', 
                ['auth' => 'password_step_required']
            );
        }

        $request=$request
            ->withAttribute('user_id',$decoded->sub)
            ->withAttribute('tenant_id',$decoded->tenant_id ?? null)
            ->withAttribute('user_role',$decoded->role ?? null)
            ->withAttribute('token_payload',(array) $decoded);
        return $handler->handle($request);
    }
}
 
<?php

namespace Infrastructure\Middlewares;

use Illuminate\Support\Facades\Context;
use Infrastructure\Exceptions\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Este middleware es el que controla si en la peticion viene el tenant_id
 * y si es asi lo inyecta en el contexto para no tener que colocarlo en cada 
 * consulta de manera manual
 */
class MultiTenantScopeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //Obtiene el tenant_id inyectado previamente por AuthenticationMiddleware
        $tenantId = $request->getAttribute('tenant_id');

        //Valida de que el tenant_id exista
        if (empty($tenantId)) {
            throw new ForbiddenException(
                'No se ha podido determinar el inquilino para procesar esta solicitud',
                ['tenant' => 'scope_missing']
            );
        }

        //Inyecta el tenant_id en el Context global de la petición HTTP
        Context::add('tenant_id', $tenantId);

        return $handler->handle($request);
    }
}
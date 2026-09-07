<?php

namespace Infrastructure\Middlewares;

use Infrastructure\Context\TenantContext;
use Infrastructure\Exceptions\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Controla si en la petición viene el tenant_id, lo inyecta en el TenantContext
 * para aislar las consultas de Eloquent y garantiza su limpieza al finalizar la solicitud.
 */
class MultiTenantScopeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Obtiene el tenant_id inyectado previamente por AuthenticationMiddleware
        $tenantId = $request->getAttribute('tenant_id');

        // Valida que el tenant_id exista
        if (empty($tenantId)) {
            throw new ForbiddenException(
                'No se ha podido determinar el inquilino para procesar esta solicitud',
                ['tenant' => 'scope_missing']
            );
        }

        // Inyecta el tenant_id en el contexto propio de la petición
        TenantContext::set((int) $tenantId);

        try {
            // Pasa la solicitud al siguiente middleware o controlador
            return $handler->handle($request);
        } finally {
            // Limpia el estado en memoria para evitar la contaminación entre peticiones (Swoole, RoadRunner, PHP-FPM)
            TenantContext::clear();
        }
    }
}
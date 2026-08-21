<?php

namespace POS\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

/**
 * Middleware para manejar CORS (Cross-Origin Resource Sharing).
 *
 * Agrega los encabezados necesarios para permitir solicitudes
 * desde dominios definidos en la variable de entorno API_CORS.
 */
class CorsMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $origin = $_ENV['API_CORS'] ?? '*';
        $method = $request->getMethod();

        // 1. Manejo de peticiones Preflight (OPTIONS)
        if ($method === 'OPTIONS') {
            $response = new Response(200);
        } else {
            // 2. Procesar la petición hacia los siguientes middlewares/controladores
            $response = $handler->handle($request);
        }

        // 3. Inyectar encabezados CORS a la respuesta final
        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-Tenant-ID')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
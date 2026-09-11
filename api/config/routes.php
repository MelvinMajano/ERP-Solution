<?php

use Infrastructure\Middlewares\AuthenticactionMiddleware;
use Infrastructure\Middlewares\MultiTenantScopeMiddleware;
use Infrastructure\Middlewares\PreAuthMiddleware;
use Modules\Core\Controllers\AuthController;
use Modules\Core\Controllers\OnboardingController;
use Modules\Inventory\Controllers\ProductController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // Prefijo global de la API (ejemplo: /api/v1)
    $basePath = $_ENV['API_BASE_PATH'] ?? '/api/v1';

    $app->group($basePath, function (RouteCollectorProxy $router) {

        $router->group('/auth', function (RouteCollectorProxy $auth) {
            // Onboarding inicial
            $auth->post('/register-tenant', OnboardingController::class);

            // Login Paso 1: Público
            $auth->post('/check-email', [AuthController::class, 'checkEmail']);

            // Login Paso 2: Protegido únicamente por PreAuthMiddleware
            $auth->post('/login-password', [AuthController::class, 'loginPassword'])
                ->add(PreAuthMiddleware::class);
        });

        $router->group('', function (RouteCollectorProxy $private) {

            // Recurso de Productos
            $private->group('/products', function (RouteCollectorProxy $products) {
                $products->get('', [ProductController::class, 'get']);
                $products->get('/{id}', [ProductController::class, 'getById']);
                $products->post('', [ProductController::class, 'create']);
                $products->put('/{id}', [ProductController::class, 'update']);
                $products->patch('/{id}/status', [ProductController::class, 'setStatus']);
                $products->delete('/{id}', [ProductController::class, 'delete']);
            });
        })
        // Encadenamiento de middlewares en orden LIFO:
        // 1. AuthenticactionMiddleware valida el JWT de sesión e inyecta tenant_id y user_id.
        // 2. MultiTenantScopeMiddleware lee el tenant_id y setea el TenantContext para Eloquent.
        ->add(MultiTenantScopeMiddleware::class)
        ->add(AuthenticactionMiddleware::class);
    });
};
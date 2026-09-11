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

    // 1. Módulo de Autenticación y Onboarding
    $app->group('/auth', function (RouteCollectorProxy $auth) {
        // Ruta pública para registro inicial de Tenant
        $auth->post('/register-tenant', OnboardingController::class);

        // Login Paso 1: Público
        $auth->post('/check-email', [AuthController::class, 'checkEmail']);

        // Login Paso 2: Protegido únicamente por PreAuthMiddleware
        $auth->post('/login-password', [AuthController::class, 'loginPassword'])
            ->add(PreAuthMiddleware::class);
    });

    // 2. Módulos Privados (Protegidos por Autenticación + Multi-Tenant Scope)
    $app->group('', function (RouteCollectorProxy $private) {

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
    ->add(MultiTenantScopeMiddleware::class)
    ->add(AuthenticactionMiddleware::class);

};
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

        // Recurso: Motivos de Movimiento
        $private->group('/movement-reasons', function (RouteCollectorProxy $reasons) {
            $reasons->get('', [MovementReasonController::class, 'get']);
            $reasons->get('/{id}', [MovementReasonController::class, 'getById']);
            $reasons->post('', [MovementReasonController::class, 'create']);
            $reasons->put('/{id}', [MovementReasonController::class, 'update']);
            $reasons->patch('/{id}/status', [MovementReasonController::class, 'setStatus']);
        });

        // Recurso: Clientes
        $private->group('/customers', function (RouteCollectorProxy $customers) {
            $customers->get('', [CustomerController::class, 'get']);
            $customers->get('/{id}', [CustomerController::class, 'getById']);
            $customers->post('', [CustomerController::class, 'create']);
            $customers->put('/{id}', [CustomerController::class, 'update']);
            $customers->patch('/{id}/status', [CustomerController::class, 'setStatus']);
        });

        // Recurso: Turnos de Caja
        $private->group('/cash-batches', function (RouteCollectorProxy $cash) {
            $cash->get('', [CashBatchController::class, 'get']);
            $cash->get('/{id}', [CashBatchController::class, 'getById']);
            $cash->get('/active/cashier/{cashierUserId}', [CashBatchController::class, 'getOpenByCashier']);
            $cash->post('/open', [CashBatchController::class, 'open']);
            $cash->post('/{id}/close', [CashBatchController::class, 'close']);
        });

    })
    ->add(MultiTenantScopeMiddleware::class)
    ->add(AuthenticactionMiddleware::class);

};
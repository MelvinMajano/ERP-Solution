<?php

use Modules\Core\Controllers\OnboardingController;
use Modules\Inventory\Controllers\ProductController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app){
    // Módulo de Autenticación y Onboarding
    $app->group('/auth', function (RouteCollectorProxy $auth) {
        // Ruta pública para registro inicial de Tenant
        $auth->post('/register-tenant', OnboardingController::class);
    });
    //Modulo de inventario
    $app->group('/products', function(RouteCollectorProxy $products){
        $products->get('',[ProductController::class, 'get']);
        $products->get('/{id}',[ProductController::class, 'getById']);
        $products->post('',[ProductController::class, 'create']);
        $products->put('/{id}',[ProductController::class, 'update']);
        $products->patch('/{id}/status',[ProductController::class, 'setStatus']);
        $products->delete('/{id}',[ProductController::class, 'delete']);
    });
};
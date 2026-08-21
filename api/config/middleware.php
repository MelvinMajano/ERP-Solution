<?php

use Infrastructure\Exceptions\Handler;
use Slim\App;

return function (App $app) {
    // convertir post a json
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    // 3. Determinar si se muestran detalles de excepción según tu variable API_PROD
    // Si API_PROD es 'true' (Producción), $displayErrorDetails será 'false' por seguridad
    $isProd = filter_var($_ENV['API_PROD'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $displayErrorDetails = !$isProd;

    // 4. Configurar ErrorMiddleware global
    $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

    // 5. Asignar tu nuevo Handler de infraestructura
    $errorMiddleware->setDefaultErrorHandler(Handler::class);

    // 6. Middleware para CORS
    $app->add(CorsMiddleware::class);
};
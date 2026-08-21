<?php

use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use POS\Middlewares\CorsMiddleware;
use POS\Middlewares\HttpErrorMiddleware;

require __DIR__ . "/../vendor/autoload.php";

// variables de entorno
Dotenv::createImmutable(__DIR__ . "/../")->load();

// crear app Slim
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath($_ENV['API_BASE_PATH']);

// convertir post a json
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

/** @var bool Indica si se deden mostrar las excepciones en la respuesta del API*/
$showException = filter_var($_ENV['API_PROD'], FILTER_VALIDATE_BOOLEAN);

$errorMiddleware = $app->addErrorMiddleware($showException, true, true);
$errorMiddleware->setDefaultErrorHandler(HttpErrorMiddleware::class);
$app->add(CorsMiddleware::class);

(require __DIR__ . '/../src/Configs/database.php')();
(require __DIR__ . '/../src/Routes/main.php')($app);

$app->run();

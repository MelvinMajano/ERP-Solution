<?php

use DI\Container;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Infrastructure\Exceptions\Handler;

require __DIR__ . "/../vendor/autoload.php";

// variables de entorno
Dotenv::createImmutable(__DIR__ . "/../")->load();

// Crea contenedor e inyecta el Handler
$container = new Container();
$container->set(Handler::class ,function(){
    return new Handler();
});

//configuracion de al app de slim
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath($_ENV['API_BASE_PATH']);

//Carga la configuración de Middlewares desde config/middleware.php
(require __DIR__ . '/../config/middleware.php')($app);

(require __DIR__ . '/../src/Configs/database.php')();
(require __DIR__ . '/../src/Routes/main.php')($app);

$app->run();

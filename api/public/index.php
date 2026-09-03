<?php

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

// variables de entorno
Dotenv::createImmutable(__DIR__ . "/../")->load();

//Configura e instancia el contenedor con ContainerBuilder
$containerBuilder = new ContainerBuilder();
//Carga las reglas de inyeccion de dependencias
$containerBuilder->addDefinitions(__DIR__ . '/../config/dependencies.php');
$container = $containerBuilder->build();
//configuracion de al app de slim
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath($_ENV['API_BASE_PATH']);

//Carga la configuración de Middlewares desde config/middleware.php
(require __DIR__ . '/../config/middleware.php')($app);

(require __DIR__ . '/../src/Configs/database.php')();
//Carga la configuracion de las rutas 
(require __DIR__ . '/../config/routes.php')($app);

//Ejecuta la aplicacion
$app->run();

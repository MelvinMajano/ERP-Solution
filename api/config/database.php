<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Facade;

//configuracion de elequent
return function (): Capsule {
    //Instancia el contenedor de Illuminate
    $container = new Container();
    $capsule = new Capsule($container);

    $capsule->addConnection([
        'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host'      => $_ENV['DB_HOST'] ?? 'mysql', // Nombre del servicio en docker-compose
        'port'      => $_ENV['DB_PORT'] ?? '3306',
        'database'  => $_ENV['DB_DATABASE'] ?? 'saas_erp_db',
        'username'  => $_ENV['DB_USERNAME'] ?? 'saas_erp_user',
        'password'  => $_ENV['DB_PASSWORD'] ?? 'saas_erp_pass_2026',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ]);

    // Asigna el despachador de eventos directamente
    $capsule->setEventDispatcher(new Dispatcher(new Container()));
    // Permite acceder a la instancia de Capsule globalmente mediante métodos estáticos
    $capsule->setAsGlobal();
    // Inicializa Eloquent ORM
    $capsule->bootEloquent();

    //Vincula el contenedor de Illuminate a las Fachadas estáticas para poder utilizar el context
    Facade::setFacadeApplication($container);

    return $capsule;
};
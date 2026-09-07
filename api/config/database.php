<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

/**
 * Configuración e inicialización de Eloquent ORM mediante Capsule Manager.
 */
return function (): Capsule {
    // Instancia el contenedor de inyección de dependencias de Illuminate
    $container = new Container();
    
    // Inicia Capsule pasando el contenedor para gestionar las dependencias de Eloquent
    $capsule = new Capsule($container);

    // Configura los parámetros de conexión a la base de datos principal
    $capsule->addConnection([
        'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host'      => $_ENV['DB_HOST'] ?? 'mysql', // Nombre del servicio en Docker
        'port'      => $_ENV['DB_PORT'] ?? '3306',
        'database'  => $_ENV['DB_DATABASE'] ?? 'saas_erp_db',
        'username'  => $_ENV['DB_USERNAME'] ?? 'saas_erp_user',
        'password'  => $_ENV['DB_PASSWORD'] ?? 'saas_erp_pass_2026',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ]);

    // Registra el despachador de eventos compartiendo la misma instancia del contenedor
    $capsule->setEventDispatcher(new Dispatcher($container));
    
    // Permite acceder a la instancia de Capsule globalmente mediante métodos estáticos
    $capsule->setAsGlobal();
    
    // Inicializa el ORM Eloquent para el mapeo de modelos
    $capsule->bootEloquent();

    return $capsule;
};
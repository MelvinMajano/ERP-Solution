<?php

use Illuminate\Database\Capsule\Manager as Capsule;

//configuracion de elequent
return function (): Capsule {
    $capsule = new Capsule();

    $capsule->addConnection([
        'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host'      => $_ENV['DB_HOST'] ?? 'mysql', // Nombre del servicio en docker-compose
        'port'      => $_ENV['DB_PORT'] ?? '3306',
        'database'  => $_ENV['DB_DATABASE'] ?? 'saas_erp_db',
        'username'  => $_ENV['DB_USERNAME'] ?? 'saas_erp_user',
        'password'  => $_ENV['DB_PASSWORD'] ?? 'saas_erp_pass',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
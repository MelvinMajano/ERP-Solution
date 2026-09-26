<?php

use Domain\Contracts\EventDispatcherInterface;
use Domain\Events\SaleCreatedEvent;
use Infrastructure\Events\EventDispatcher;
use Infrastructure\Listeners\RegisterCashMovement;
use Infrastructure\Listeners\RegisterInventoryKardexMovement;
use Psr\Container\ContainerInterface;

/**
 * CONFIGURACIÓN Y SUSCRIPCIÓN DE EVENTOS (PSR-14)
 */
return [
    EventDispatcherInterface::class => function (ContainerInterface $c) {
        $dispatcher = new EventDispatcher();

        // Suscripción del Listener de Kardex al evento de Venta Creada
        $dispatcher->listen(
            SaleCreatedEvent::class,
            $c->get(RegisterInventoryKardexMovement::class)
        );

        // Suscripción del Listener de Caja al evento de Venta Creada
        $dispatcher->listen(
            SaleCreatedEvent::class,
            $c->get(RegisterCashMovement::class)
        );

        return $dispatcher;
    },
];
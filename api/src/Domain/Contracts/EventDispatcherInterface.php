<?php

namespace Domain\Contracts;

/**
 * Interface EventDispatcherInterface
 *
 * Contrato de abstracción para el despachado de eventos de dominio en Slim 4.
 *
 * @package Domain\Contracts
 */
interface EventDispatcherInterface
{
    /**
     * Despacha un evento de dominio a sus suscriptores registrados.
     *
     * @param object $event Instancia del evento a emitir.
     * @return object
     */
    public function dispatch(object $event): object;
}
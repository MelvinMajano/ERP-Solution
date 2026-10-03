<?php

namespace Infrastructure\Events;

use Domain\Contracts\EventDispatcherInterface;

/**
 * Class EventDispatcher
 *
 * Implementación desacoplada para el registro y despacho de eventos de dominio en Slim 4.
 *
 * @package Infrastructure\Events
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, array<int, callable|object>>
     */
    private array $listeners = [];

    /**
     * Registra un listener o callback para un evento específico.
     *
     * @param string $eventClass Nombre cualificado de la clase del evento.
     * @param callable|object $listener Listener o invokable.
     * @return void
     */
    public function listen(string $eventClass, callable|object $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    /**
     * Despacha el evento ejecutando todos sus listeners registrados.
     *
     * @param object $event
     * @return object
     */
    public function dispatch(object $event): object
    {
        $eventClass = get_class($event);

        if (!isset($this->listeners[$eventClass])) {
            return $event;
        }

        foreach ($this->listeners[$eventClass] as $listener) {
            if (is_callable($listener)) {
                $listener($event);
            } elseif (method_exists($listener, 'handle')) {
                $listener->handle($event);
            }
        }

        return $event;
    }
}
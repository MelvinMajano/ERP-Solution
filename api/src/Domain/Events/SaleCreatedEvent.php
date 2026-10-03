<?php
namespace Domain\Events;

use Domain\Entities\SaleInvoice;
use Domain\ValueObjects\SaleItemInput;

/**
 * Class SaleCreatedEvent
 *
 * Evento de dominio emitido tras la persistencia exitosa de una factura de venta.
 *
 * @package Domain\Events
 */
class SaleCreatedEvent
{
    /**
     * @param SaleInvoice $saleInvoice Entidad de la factura creada.
     * @param array<int, SaleItemInput> $items Colección de ítems procesados.
     */
    public function __construct(
        public readonly SaleInvoice $saleInvoice,
        public readonly array $items
    ) {}
}
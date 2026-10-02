<?php

namespace Infrastructure\Listeners;

use Domain\Events\SaleCreatedEvent;
use Modules\Inventory\DTOs\InventoryMovement\RegisterMovementDTO;
use Modules\Inventory\Services\KardexService;

class RegisterInventoryKardexMovement
{
    public function __construct(
        private readonly KardexService $kardexService
    ) {}

    public function handle(SaleCreatedEvent $event): void
    {
        $invoice = $event->saleInvoice;

        $notes = "Salida por venta de factura #{$invoice->invoice_number}";
        if (!empty($invoice->notes)) {
            $notes .= " - Nota: {$invoice->notes}";
        }

        foreach ($event->items as $item) {
            $this->kardexService->registerOutput(new RegisterMovementDTO(
                productId: $item->productId,
                movementReasonId: 1, // ID asignado al motivo "Salida por Venta"
                movementType: 'OUT',
                quantity: (float) $item->quantity,
                referenceType: 'SALE_INVOICE',
                referenceId: $invoice->id,
                description: $notes,
                unitCost: (float) ($item->unitPrice ?? 0.0),
                createdBy: $invoice->created_by,
                tenantId: $invoice->tenant_id
            ));
        }
    }
}

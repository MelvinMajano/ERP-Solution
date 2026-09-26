<?php

namespace Infrastructure\Listeners;

use Domain\Contracts\CashBatchRepositoryInterface;
use Domain\Events\SaleCreatedEvent;

/**
 * Class RegisterCashMovement
 *
 * Listener encargado de asentar los totales vendidos en el turno de caja activo.
 */
class RegisterCashMovement
{
    public function __construct(
        private readonly CashBatchRepositoryInterface $cashBatchRepository
    ) {}

    /**
     * Incrementa los acumulados de venta del turno de caja.
     *
     * @param SaleCreatedEvent $event
     * @return void
     */
    public function handle(SaleCreatedEvent $event): void
    {
        $invoice = $event->saleInvoice;

        if ($invoice->cash_batch_id === null) {
            return;
        }

        $cashBatch = $this->cashBatchRepository->findById($invoice->cash_batch_id);

        if (!$cashBatch) {
            return;
        }

        $this->cashBatchRepository->update($cashBatch->id, [
            'expected_amount' => $cashBatch->expected_amount + $invoice->net_total,
            'total_sales'     => $cashBatch->total_sales + $invoice->net_total,
        ]);
    }
}
<?php

namespace Modules\Billing\Factories;

use Domain\Entities\SaleInvoice;
use Domain\ValueObjects\InvoiceCalculationSummary;
use Modules\Billing\DTOs\CreateSaleDTO;

/**
 * Class SaleInvoiceFactory
 *
 * Asume la responsabilidad única de instanciar y ensamblar la entidad SaleInvoice.
 *
 * @package Modules\Billing\Factories
 */
class SaleInvoiceFactory
{
    /**
     * Instancia una nueva factura de venta.
     *
     * @param CreateSaleDTO $dto DTO con la información de origen.
     * @param InvoiceCalculationSummary $summary Resumen financiero calculado.
     * @param string $invoiceNumber Correlativo asignado.
     * @return SaleInvoice
     */
    public function createFromDTO(
        CreateSaleDTO $dto,
        InvoiceCalculationSummary $summary,
        string $invoiceNumber
    ): SaleInvoice {
        $invoice = new SaleInvoice();
        $invoice->customer_id     = $dto->customerId;
        $invoice->cashier_user_id = $dto->cashierUserId; 
        $invoice->cash_batch_id   = $dto->cashBatchId;   
        $invoice->invoice_number  = $invoiceNumber;
        $invoice->subtotal        = $summary->subtotal;
        $invoice->discount_total  = $summary->discountTotal;
        $invoice->tax_total       = $summary->taxTotal;
        $invoice->net_total       = $summary->netTotal;
        $invoice->status          = 'ISSUED';
        $invoice->created_by      = $dto->cashierUserId; 

        return $invoice;
    }
}
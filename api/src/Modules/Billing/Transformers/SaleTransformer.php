<?php

namespace Modules\Billing\Transformers;

use Domain\Entities\SaleInvoice;
use Domain\Entities\SaleInvoiceDetail;

class SaleTransformer
{
    /**
     * Transforma una entidad SaleInvoice a un arreglo estructurado.
     *
     * @param SaleInvoice $invoice
     * @return array
     */
    public function transform(SaleInvoice $invoice): array
    {
        return [
            'id'             => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'issue_date'     => $invoice->issue_date?->toIso8601String() ?? $invoice->created_at?->toIso8601String(),
            'status'         => $invoice->status,
            'customer_id'    => $invoice->customer_id,
            'cash_batch_id'  => $invoice->cash_batch_id,
            'summary'        => [
                'subtotal'       => (float) $invoice->subtotal,
                'tax_amount'     => (float) $invoice->tax_amount,
                'discount_amount'=> (float) $invoice->discount_amount,
                'net_total'      => (float) $invoice->net_total,
            ],
            'notes'          => $invoice->notes,
            'items'          => $invoice->details ? $invoice->details->map(fn(SaleInvoiceDetail $item) => [
                'id'          => $item->id,
                'product_id'  => $item->product_id,
                'quantity'    => (float) $item->quantity,
                'unit_price'  => (float) $item->unit_price,
                'subtotal'    => (float) $item->subtotal,
                'tax_rate'    => (float) $item->tax_rate,
                'tax_amount'  => (float) $item->tax_amount,
                'total_amount'=> (float) $item->total_amount,
            ])->toArray() : [],
            'created_by'     => $invoice->created_by,
            'created_at'     => $invoice->created_at?->toIso8601String(),
        ];
    }
}
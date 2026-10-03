<?php

namespace Modules\Billing\Transformers;

use Domain\Entities\SaleInvoice;
use Domain\Entities\SaleInvoiceDetail;

class SaleTransformer
{
    public function transform(SaleInvoice $invoice, ?string $notes = null): array
    {
        return [
            'id'             => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'issue_date'     => $invoice->created_at?->toIso8601String(),
            'status'         => $invoice->status,
            'customer_id'    => $invoice->customer_id,
            'cash_batch_id'  => $invoice->cash_batch_id,
            'summary'        => [
                'subtotal'       => (float) $invoice->subtotal,
                'tax_amount'     => (float) $invoice->tax_total,
                'discount_amount' => (float) $invoice->discount_total,
                'net_total'      => (float) $invoice->net_total,
            ],
            'notes'          => $invoice->notes ?? $notes,
            'items' => $invoice->details ? $invoice->details->map(fn(SaleInvoiceDetail $item) => [
                'id'           => $item->id,
                'product_id'   => $item->product_id,
                'quantity'     => (float) $item->quantity,
                'unit_price'   => (float) $item->unit_price,
                'subtotal'     => (float) ($item->subtotal ?? $item->line_subtotal ?? 0), 
                'tax_rate'     => (float) $item->tax_rate,
                'tax_amount'   => (float) ($item->tax_amount ?? $item->line_tax_amount ?? 0), 
                'total_amount' => (float) ($item->total_amount ?? $item->line_net_total ?? 0),
            ])->toArray() : [],
            'created_by'     => $invoice->created_by,
            'created_at'     => $invoice->created_at?->toIso8601String(),
        ];
    }
}

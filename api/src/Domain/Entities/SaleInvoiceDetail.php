<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Detalle o renglón individual de una Factura de Venta.
 *
 * Mantiene un snapshot (fotografía histórica) de los precios, costos y nombres
 * del producto al momento exacto de la transacción para proteger la integridad auditada.
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $product_id
 * @property string $product_name Snapshot histórico del nombre.
 * @property float $quantity
 * @property float $unit_price Precio unitario cobrado.
 * @property float $unit_cost Snapshot del costo unitario al vender.
 * @property float $line_subtotal (quantity * unit_price)
 * @property float $line_net_total Monto final de la línea cobrado al cliente.
 */
class SaleInvoiceDetail extends Model
{
    use BelongsToTenant;

    protected $table = 'sales_invoice_details';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'unit_cost',
        'tax_rate',
        'line_subtotal',
        'discount_percentage',
        'line_discount',
        'line_tax_amount',
        'line_net_total',
        'exempt_amount',
    ];

    protected $casts = [
        'quantity'            => 'float',
        'unit_price'          => 'float',
        'unit_cost'           => 'float',
        'tax_rate'            => 'float',
        'line_subtotal'       => 'float',
        'discount_percentage' => 'float',
        'line_discount'       => 'float',
        'line_tax_amount'     => 'float',
        'line_net_total'      => 'float',
        'exempt_amount'       => 'float',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SaleInvoice::class, 'invoice_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
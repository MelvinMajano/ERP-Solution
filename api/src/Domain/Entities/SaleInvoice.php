<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Cabecera de Factura o Venta emitida.
 *
 * Administra el estado global de la transacción de venta y el cumplimiento
 * de reglas de anulación fiscal/comercial.
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $customer_id
 * @property string $invoice_number
 * @property int $cashier_user_id
 * @property int|null $cai_id
 * @property int|null $cash_batch_id
 * @property float $subtotal
 * @property float $discount_total
 * @property float $tax_total
 * @property float $net_total
 * @property string $status Estado de la venta: ISSUED, VOIDED, PENDING.
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SaleInvoiceDetail> $details
 */
class SaleInvoice extends Model
{
    use BelongsToTenant;

    protected $table = 'sales_invoices';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'invoice_number',
        'cashier_user_id',
        'cai_id',
        'cash_batch_id',
        'exempt_order_number',
        'exempt_certificate_number',
        'exempt_sag_number',
        'subtotal',
        'discount_total',
        'tax_total',
        'net_total',
        'status',
        'created_by',
    ];

    protected $casts = [
        'subtotal'       => 'float',
        'discount_total' => 'float',
        'tax_total'      => 'float',
        'net_total'      => 'float',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    /**
     * Líneas o ítems individuales facturados en esta transacción.
     */
    public function details(): HasMany
    {
        return $this->hasMany(SaleInvoiceDetail::class, 'invoice_id');
    }

    /**
     * Garantiza que la factura se encuentre en un estado mutable o anulable.
     *
     * @throws DomainException Si la factura ya fue anulada previamente.
     */
    public function assertIsEditableOrVoidable(): void
    {
        if ($this->status === 'VOIDED') {
            throw new DomainException("La factura N° '{$this->invoice_number}' ya se encuentra anulada.");
        }
    }

    /**
     * Cambia el estado de la factura a anulada previa validación de invariantes.
     *
     * @throws DomainException
     */
    public function markAsVoided(): void
    {
        $this->assertIsEditableOrVoidable();
        $this->status = 'VOIDED';
    }
}
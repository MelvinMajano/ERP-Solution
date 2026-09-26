<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Registro inmutable del Kardex de Inventario.
 *
 * Representa la pista de auditoría física e histórica de las variaciones de stock
 * y valoraciones de un producto.
 *
 * @property int $id Identificador único del registro.
 * @property int $tenant_id Empresa a la que pertenece la transacción.
 * @property int $product_id Producto afectado.
 * @property int $movement_reason_id Causa o motivo del movimiento.
 * @property string $type Direccionalidad: 'IN', 'OUT' o 'ADJUSTMENT'.
 * @property float $quantity Cantidad física movilizada.
 * @property float $previous_stock Stock previo al movimiento.
 * @property float $new_stock Stock resultante luego del movimiento.
 * @property float $unit_cost Costo unitario asentado al momento del movimiento.
 * @property string $reference_type Origen (ej. SALE_INVOICE, PURCHASE_INVOICE, MANUAL_ADJUSTMENT).
 * @property int|null $reference_id ID del documento emisor.
 * @property string|null $notes Observaciones adicionales.
 * @property int $created_by Usuario que ejecutó el movimiento.
 * @property \Illuminate\Support\Carbon $created_at
 */
class InventoryMovement extends Model
{
    use BelongsToTenant;

    protected $table = 'inventory_movements';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'movement_reason_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'unit_cost',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity'       => 'float',
        'previous_stock' => 'float',
        'new_stock'      => 'float',
        'unit_cost'      => 'float',
        'created_at'     => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(MovementReason::class, 'movement_reason_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
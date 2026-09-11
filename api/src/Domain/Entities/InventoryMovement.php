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
 * @property int $id
 * @property int $product_id
 * @property int $movement_reason_id
 * @property string $type Direccionalidad: 'IN' u 'OUT'.
 * @property float $quantity Cantidad física movilizada.
 * @property float $previous_stock Stock previo al movimiento.
 * @property float $new_stock Stock resultante luego del ajuste.
 * @property float $unit_cost Costo unitario asentado al momento del movimiento.
 * @property string $reference_type Origen del movimiento (ej. SALE, PURCHASE, ADJUSTMENT).
 * @property int|null $reference_id ID del documento emisor según el reference_type.
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(MovementReason::class, 'movement_reason_id');
    }
}
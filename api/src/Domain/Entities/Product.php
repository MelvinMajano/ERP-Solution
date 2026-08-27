<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Infrastructure\Traits\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;

    protected $table = 'products';

    protected $fillable = [
        'tenant_id',
        'sku',
        'barcode',
        'name',
        'primary_supplier_id',
        'price',
        'cost',
        'current_stock',
        'is_service',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'price'         => 'float',
        'cost'          => 'float',
        'current_stock' => 'float',
        'is_service'    => 'boolean',
        'is_active'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function primarySupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'primary_supplier_id');
    }

    /**
     * Regla: El producto/servicio debe estar activo para operar.
     */
    public function assertIsActive(): void
    {
        if (!$this->is_active) {
            throw new DomainException("El ítem '{$this->name}' se encuentra inactivo.");
        }
    }

    /**
     * Valida la existencia física suficiente antes de realizar una salida (Venta/Merma).
     */
    public function assertStockAvailable(float $quantity): void
    {
        $this->assertIsActive();

        if ($this->current_stock < $quantity) {
            throw new DomainException(
                "Stock insuficiente para '{$this->name}'. Disponible: {$this->current_stock}, Solicitado: {$quantity}."
            );
        }
    }

    public function updateCost(float $newCost): void
    {
        if ($newCost < 0) {
            throw new DomainException("El costo del producto no puede ser negativo.");
        }
        $this->cost = $newCost;
    }

    public function applyStockDelta(float $delta): void
    {
        $this->current_stock += $delta;
    }
}
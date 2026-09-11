<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Causa o justificación parametrizada para afectar el inventario.
 *
 * @property int $id
 * @property string $name Ej. "Venta POS", "Ajuste por Merma", "Compra Directa".
 * @property string $movement_type Tipo de dirección del stock: 'IN' u 'OUT'.
 * @property bool $is_active
 */
class MovementReason extends Model
{
    use BelongsToTenant;

    protected $table = 'movement_reasons';

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'name',
        'movement_type',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Invariante que valida si el motivo está habilitado para ser utilizado en transacciones.
     *
     * @throws DomainException
     */
    public function assertIsActive(): void
    {
        if (!$this->is_active) {
            throw new DomainException("El motivo de movimiento '{$this->name}' está inactivo.");
        }
    }
}
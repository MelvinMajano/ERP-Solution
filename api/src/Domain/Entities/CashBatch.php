<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

class CashBatch extends Model
{
    use BelongsToTenant;

    protected $table = 'cash_batches';

    protected $fillable = [
        'tenant_id',
        'cashier_user_id',
        'opening_date',
        'closing_date',
        'opening_balance',
        'expected_amount',
        'actual_amount',
        'difference',
        'total_sales',
        'status',
        'created_by',
    ];

    protected $casts = [
        'cashier_user_id' => 'integer',
        'opening_balance' => 'float',
        'expected_amount' => 'float',
        'actual_amount'  => 'float',
        'difference'     => 'float',
        'total_sales'    => 'float',
        'opening_date'   => 'datetime',
        'closing_date'   => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'created_by'     => 'integer',
    ];

    /**
     * Invariante de entidad: Valida que la caja esté abierta.
     *
     * @throws DomainException
     */
    public function assertIsOpen(): void
    {
        if ($this->status !== 'OPEN') {
            throw new DomainException("El turno de caja con ID {$this->id} ya se encuentra cerrado.");
        }
    }
}
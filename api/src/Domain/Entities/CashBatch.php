<?php

declare(strict_types=1);

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
        'user_id',
        'opening_balance',
        'closing_balance',
        'status',
        'notes',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opening_balance' => 'float',
        'closing_balance' => 'float',
        'opened_at'       => 'datetime',
        'closed_at'       => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    /**
     * Invariante de entidad: Valida que la caja esté abierta.
     *
     * @throws DomainException
     */
    public function assertIsOpen(): void
    {
        if ($this->status !== 'open') {
            throw new DomainException("El turno de caja con ID {$this->id} ya se encuentra cerrado.");
        }
    }

    /**
     * Invariante de entidad: Valida si la caja ya está cerrada.
     *
     * @throws DomainException
     */
    public function assertIsClosed(): void
    {
        if ($this->status === 'closed') {
            throw new DomainException("El turno de caja ya fue cerrado previamente.");
        }
    }
}
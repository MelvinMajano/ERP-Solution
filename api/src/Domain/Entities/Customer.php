<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Representa la entidad de dominio para el registro de clientes en el Tenant activo.
 * 
 * Gestiona los datos fiscales (RTN) y de contacto para la emisión de comprobantes de venta.
 */
class Customer extends Model
{
    use BelongsToTenant;

    protected $table = 'customers';

    protected $fillable = [
        'tenant_id',
        'rtn',
        'name',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Valida que el cliente esté habilitado operacionalmente para facturar.
     *
     * @throws DomainException Si el cliente se encuentra inactivo.
     */
    public function assertIsActive(): void
    {
        if (!$this->is_active) {
            throw new DomainException("El cliente '{$this->name}' se encuentra inactivo.");
        }
    }
}
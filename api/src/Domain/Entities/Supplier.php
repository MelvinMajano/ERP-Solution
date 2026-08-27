<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Traits\BelongsToTenant;

class Supplier extends Model
{
    use BelongsToTenant;

    protected $table = 'suppliers';

    protected $fillable = [
        'tenant_id',
        'rtn',
        'name',
        'business_name',
        'email',
        'phone',
        'address',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'primary_supplier_id');
    }
    
    //valida que el proveedor este activo de lo contrario laza una excepcion
    public function assertIsActive(): void
    {
        if (!$this->is_active) {
            throw new DomainException("El proveedor '{$this->name}' se encuentra inactivo.");
        }
    }
}
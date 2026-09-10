<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

class TenantModule extends Model
{
    use BelongsToTenant;

    protected $table = 'tenant_modules';

    protected $fillable = [
        'tenant_id',
        'module_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];
}
<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Infrastructure\Traits\BelongsToTenant;

class Role extends Model
{
    use BelongsToTenant;

    protected $table = 'roles';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'created_by',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'rol_permissions',
            'rol_id',
            'permission_id'
        );
    }
}
<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

class User extends Model
{

    use BelongsToTenant;

    protected $table = 'users';

    protected $fillable = [
        'tenant_id',
        'rol_id',
        'first_names',
        'last_names',
        'username',
        'email',
        'password',
        'is_active',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];
}
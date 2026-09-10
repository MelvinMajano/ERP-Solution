<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $table = 'tenants';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_name',
        'subdomain',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Regla invariable: Todo tenant nuevo inicia activo por defecto
    protected $attributes = [
        'is_active' => true,
    ];

    //formatea el subdominio en minuscualas y sin espacio
    public function setSubdomainAttribute(string $value):void
    {
        $cleanSubdomain =strtolower(trim($value));
        if(empty($cleanSubdomain))
            {
                throw new DomainException(
                message: "El subdominio es requerido.",
                errors: ['subdomain' => 'El subdominio no puede estar vacío.']
            );
        }
        $this->attributes['subdomain'] = $cleanSubdomain;
    }
    // Relaciones
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'tenant_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(TenantModule::class, 'tenant_id');
    }
}
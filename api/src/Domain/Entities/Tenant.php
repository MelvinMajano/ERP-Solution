<?php

namespace Domain\Entities;

use Domain\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Entidad de Dominio / Modelo Eloquent para la gestión de Empresas (Tenants).
 *
 * Representa a las organizaciones o clientes de la plataforma SaaS.
 *
 * @property int $id Identificador único del tenant.
 * @property string $company_name Nombre de la empresa o razón social.
 * @property string $subdomain Subdominio único para el aislamiento del cliente.
 * @property bool $is_active Estado de habilitación del cliente en la plataforma.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TenantModule> $modules
 */
class Tenant extends Model
{
    /**
     * Tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'tenants';

    /**
     * Clave primaria de la tabla.
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Atributos asignables en masa.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'subdomain',
        'is_active',
    ];

    /**
     * Conversión de tipos de atributos.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Valores por defecto de los atributos.
     *
     * Regla invariable: Todo tenant nuevo inicia activo por defecto.
     * 
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Mutador para formatear y garantizar las invariantes del subdominio.
     *
     * @param string $value Subdominio a limpiar y asignar.
     * @return void
     * 
     * @throws DomainException Si el subdominio resultante está vacío.
     */
    public function setSubdomainAttribute(string $value): void
    {
        $cleanSubdomain = strtolower(trim($value));

        if (empty($cleanSubdomain)) {
            throw new DomainException(
                message: 'El subdominio es requerido.',
                errors: ['subdomain' => 'El subdominio no puede estar vacío.']
            );
        }

        $this->attributes['subdomain'] = $cleanSubdomain;
    }

    /**
     * Relación uno a muchos con los usuarios del Tenant.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    /**
     * Relación uno a muchos con los roles pertenecientes al Tenant.
     *
     * @return HasMany
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'tenant_id');
    }

    /**
     * Relación uno a muchos con los módulos habilitados para el Tenant.
     *
     * @return HasMany
     */
    public function modules(): HasMany
    {
        return $this->hasMany(TenantModule::class, 'tenant_id');
    }
}
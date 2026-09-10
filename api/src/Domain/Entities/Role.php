<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Entidad de Dominio / Modelo Eloquent para Roles de Acceso.
 *
 * @property int $id Identificador del rol.
 * @property int $tenant_id ID de la empresa propietaria.
 * @property string $name Nombre del rol (ej. Administrador, Cajero).
 * @property string|null $description Descripción de los privilegios.
 * @property int|null $created_by ID del usuario creador.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 */
class Role extends Model
{
    use BelongsToTenant;

    /**
     * Tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'roles';

    /**
     * Atributos asignables en masa.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'created_by',
    ];

    /**
     * Relación muchos a muchos con la entidad Permission.
     *
     * @return BelongsToMany
     */
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
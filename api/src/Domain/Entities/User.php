<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Entidad de Dominio / Modelo Eloquent para los Usuarios del Sistema.
 *
 * Utiliza la Trait BelongsToTenant para asegurar el aislamiento Multi-Tenant.
 *
 * @property int $id Identificador del usuario.
 * @property int $tenant_id ID de la empresa a la que pertenece el usuario.
 * @property int $rol_id ID del rol asignado.
 * @property string $first_names Nombres del usuario.
 * @property string $last_names Apellidos del usuario.
 * @property string $username Nombre de usuario único por tenant.
 * @property string $email Correo electrónico único por tenant.
 * @property string $password Hash de la contraseña.
 * @property bool $is_active Estado de acceso del usuario.
 * @property int|null $created_by Usuario que registró la cuenta.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Model
{
    use BelongsToTenant;

    /**
     * Tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * Atributos asignables en masa.
     * 
     * @var array<int, string>
     */
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

    /**
     * Atributos ocultos para serialización JSON.
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];
}
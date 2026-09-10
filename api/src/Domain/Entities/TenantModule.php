<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\BelongsToTenant;

/**
 * Entidad de Dominio / Modelo Eloquent para Módulos Habilitados por Tenant.
 *
 * @property int $tenant_id ID de la empresa.
 * @property string $module_id Identificador del módulo.
 * @property bool $is_active Indica si el módulo está habilitado para la empresa.
 */
class TenantModule extends Model
{
    use BelongsToTenant;

    /**
     * Tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'tenant_modules';

    /**
     * Atributos asignables en masa.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'module_id',
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
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];
}
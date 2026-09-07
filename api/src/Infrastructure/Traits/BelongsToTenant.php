<?php

namespace Infrastructure\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Context\TenantContext;

/**
 * BelongsToTenant asigna e inyecta automáticamente el tenant_id en las consultas y creaciones de Eloquent.
 * 
 * Utiliza TenantContext para obtener la identidad del inquilino activo de forma desacoplada
 * y thread-safe, evitando dependencias de Facades de Laravel.
 * 
 * @mixin Model
 * @method static void addGlobalScope(string $scope, \Closure $implementation)
 * @method static void creating(\Closure|string $callback)
 */
trait BelongsToTenant
{
    /**
     * Eloquent ejecuta este método automáticamente al inicializar la entidad
     * gracias a la convención de renombrado boot[NombreDelTrait].
     */
    protected static function bootBelongsToTenant(): void
    {
        // Modifica las consultas de Eloquent (SELECT, UPDATE, DELETE) antes de enviarlas a la base de datos
        static::addGlobalScope('tenant_scope', function (Builder $builder) {

            // Extrae la identidad del inquilino activo desde el contexto aislado
            $tenantId = TenantContext::get();

            if ($tenantId !== null) {
                // Agrega la cláusula SQL de filtrado por inquilino
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenantId);
            }
        });

        // Este método se dispara justo antes de ejecutar un INSERT en la base de datos
        static::creating(function (Model $model) {
            $tenantId = TenantContext::get();

            /**
             * Si por algún motivo especial el desarrollador asignó manualmente un 
             * tenant_id específico antes de guardar, 
             * el Trait respeta esa asignación previa y no la sobreescribe.
             */
            if ($tenantId !== null && empty($model->tenant_id)) {
                // Inyecta automáticamente el tenant_id en la instancia del modelo.
                $model->tenant_id = $tenantId;
            }
        });
    }
}
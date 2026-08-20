<?php

namespace Infrastructure\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Context;

/**
 * BelongsToTenant se encarga de asignar el tenantID sin tener que hacerlo 
 * manualmente en cada consulta
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
        //modifica la consultas de eloquent antes de que las consultas sean enviadas a la base de datos
        static::addGlobalScope('tenant_scope', function (Builder $builder) {

            //Extrae la identidad del inquilino activo durante la petición HTTP.
            $tenantId = Context::get('tenant_id');

            if ($tenantId !== null) {
                //Agrega la cláusula SQL
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenantId);
            }
        });

        //este metodo se dispara justo antes de ejecutar un insert en al tabla de la base de datos 
        static::creating(function ($model) {
            $tenantId = Context::get('tenant_id');

            /**
             * Si por algún motivo especial el desarrollador asignó manualmente un 
             * tenant_id específico antes de guardar, 
             * el Trait respeta esa asignación previa y no la sobreescribe.
             */
            if ($tenantId !== null && empty($model->tenant_id)) {
                //Inyecta automáticamente $model->tenant_id = $tenantId en la instancia del modelo.
                $model->tenant_id = $tenantId;
            }
        });
    }
}

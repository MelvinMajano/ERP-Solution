<?php

namespace Domain\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Entidad de Dominio / Modelo Eloquent para Permisos Globales del Sistema.
 *
 * @property int $id Identificador único del permiso.
 * @property string $name Nombre descriptivo de la acción.
 * @property string $code Código único del permiso (ej. inventory.products.create).
 * @property string $module_id Clave del módulo al que pertenece.
 * @property string|null $description Explicación detallada de la acción.
 */
class Permission extends Model
{
    /**
     * Tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'permissions';
    
    /**
     * Atributos asignables en masa.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'module_id',
        'description',
    ];
}
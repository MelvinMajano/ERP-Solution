<?php

namespace Modules\Inventory\Repositories;

use Domain\Contracts\MovementReasonRepositoryInterface;
use Domain\Entities\MovementReason;
use Infrastructure\Base\BaseRepository;

/**
 * Repositorio de Infraestructura para la gestión de Motivos de Movimiento.
 *
 * Encargado de la persistencia y consultas a la base de datos para la entidad MovementReason
 * utilizando Eloquent ORM y extendiendo la funcionalidad base de ordenamiento y filtrado.
 */
class MovementReasonRepository extends BaseRepository implements MovementReasonRepositoryInterface
{
    /**
     * Columnas permitidas para ordenamiento dinámico.
     *
     * @var array<int, string>
     */
    protected array $sortableColumns = ['id', 'name', 'movement_type', 'is_active', 'created_at'];

    /**
     * Columnas habilitadas para búsquedas por coincidencia parcial (LIKE).
     *
     * @var array<int, string>
     */
    protected array $likeColumns = ['name'];

    /**
     * Crea una nueva instancia del repositorio.
     *
     * @param MovementReason $model Modelo de Eloquent inyectado.
     */
    public function __construct(MovementReason $model)
    {
        parent::__construct($model);
    }

    /**
     * Busca un motivo de movimiento por su ID primario.
     *
     * @param int|string $id Identificador único del motivo.
     * @return MovementReason|null Retorna la entidad si existe, o null en caso contrario.
     */
    public function findById(int|string $id): ?MovementReason
    {
        /** @var MovementReason|null */
        return parent::findById($id);
    }

    /**
     * Busca un motivo de movimiento por su nombre dentro del tenant actual.
     *
     * @param string $name Nombre exacto del motivo a consultar.
     * @param int|string|null $ignoreId ID del registro a ignorar (útil para validaciones en actualizaciones).
     * @return MovementReason|null Entidad encontrada o null si no existe.
     */
    public function findByName(string $name, int|string|null $ignoreId = null): ?MovementReason
    {
        /** @var MovementReason|null */
        return $this->query()
            ->where('name', $name)
            ->when($ignoreId, static fn($query) => $query->where('id', '!=', $ignoreId))
            ->first();
    }

    /**
     * Registra un nuevo motivo de movimiento en la base de datos.
     *
     * @param array<string, mixed> $data Datos validados para la creación.
     * @return MovementReason Instancia del motivo recien creado.
     */
    public function create(array $data): MovementReason
    {
        /** @var MovementReason */
        return parent::create($data);
    }
}
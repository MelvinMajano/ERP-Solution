<?php

namespace Infrastructure\Base;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Infrastructure\Contracts\RepositoryInterface;


/**
 * @template TModel of Model
 */
abstract class BaseRepository implements RepositoryInterface{

    /**
     * @var TModel
     */
    protected Model $model;

    /**
     * @var array<int, string>
     */
    protected array $sortableColumns = ['id'];

    /**
     * @var array<int, string>
     */
    protected array $likeColumns = [];

    /**
     * @param TModel $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retorna una nueva consulta siempre que se ejecute la funcion
     *query()
     * 
     * @return Builder<TModel>
     */
    protected function query():Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Retorna la instancia del modelo que se busco por id
     *
     * @param int|string $id
     * @return TModel|null
     */
    public function findById(int | string $id):?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Aplica los filtros dinámicos recibidos en $params['filters'].
     */
    protected function applyDynamicFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            /**
             * Valida si los valores de filters son null o vacio se hace un
             * continue lo representaria que no se apliquen dichos filtros a la query
             * y esta se retorna sin agregarle nada.
             */
            if ($value === null || $value === '') {
                continue;
            }

            /**
             * Si se llega aqui significa de que si hau filtros, y se tiene que deterniminar si son
             * filtros parciales o exactos, para ello se accede con polimorfismo a la propiedad sobreescrita 
             * @likeColumns en la clase hija o que herde de BaseRepository
             */
            if (in_array($field, $this->likeColumns, true)) {
                $query->where($field, 'LIKE', "%{$value}%");
            //Si dicho filtro no se encuentra en likeColumns se considera una busqueda exacta
            } else {
                $query->where($field, '=', $value);
            }
        }

        return $query;
    }

    /**
     * Obtiene todos los registros paginados y ordenados 
     *
     * @param array<int, string> $params Parámetros de consulta (paginación, ordenamiento y filtros) 
     * @return LengthAwarePaginator<TModel> Objeto con la data paginada y metadatos
     */
    public function all(array $params = []):LengthAwarePaginator
    {
        //Se obtien los datos de paginacion
        $page = (int) ($params['page'] ?? 1);
        $pageSize = (int) ($params['pageSize'] ?? 10);

        //los datos de orden y direccion de la misma
        $rawSortBy = $params['sortBy'] ?? null;
        $rawSortDir = $params['sortDir'] ?? null;

        //se valida si los paramatros por los que se quiere ordenar son validos estan en sortableColumns
        $sortBy = in_array($rawSortBy, $this->sortableColumns, true) ? $rawSortBy : 'id';
        $sortDir = strtolower((string) $rawSortDir) === 'desc' ? 'desc' : 'asc';

        //Crea una nueva instancia de la query
        $query = $this->query();

        //Ejecuta en applyDynamicFilters que es el que tiene la logiaca de los filtros 
        if (isset($params['filters']) && is_array($params['filters'])) {
            $this->applyDynamicFilters($query, $params['filters']);
        }

        //Ejecuta la consulta en la BD y retorna los datos paginados
        return $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(
                perPage: $pageSize,
                columns: ['*'],
                pageName: 'page',
                page: $page
            );
    }

    /**
     * Crea un nuevo registro en la base de datos.
     *
     * @param array<string, mixed> $data
     * @return TModel
     */
    public function create(array $data):Model{
        return $this->query()->create($data);
    }

    /**
     * Actualiza un registro existente por su ID.
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @var $record es la instancia del modelo ya que se utilza findById
     * que retorna el modelo cuando encuentra una considencia 
     * @return bool
     */
    public function update(int | string $id,array $data):bool{
        $record = $this->findById($id);
        if(!$record){
            return false;
        }
        return (bool) $record->update($data);
    }

    /**
     * Elimina un registro por su ID.
     * 
     * @param int|string $id
     * @var $record lo mismo que en el update, se optiene el modelo y
     * luego en este caso se elimina ese registro
     * @return bool
     */
    public function delete(int | string $id):bool{
        $record= $this->findById($id);
        if(!$record){
            return false;
        }
        return (bool) $record->delete();
    }

    /**
     * Cambia el estado de un registro por su ID.
     * 
     * @param int|string $id, bool $isActive
     * @var $record lo mismo que en el update y el delete, se optiene el modelo y
     * Se actualiza el estado de ese registro 
     * @return bool
     */
   public function setStatus(int|string $id, bool $isActive): bool
    {
        $record = $this->findById($id);
        return $record ? (bool) $record->update(['is_active' => $isActive]) : false;
    }
}
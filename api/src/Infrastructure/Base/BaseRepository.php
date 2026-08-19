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
     * Obtiene todos los registros.
     *
     * @param array<int, string> $columns si no se especifica los campos, la cosulta 
     * traera todos los campos que tenga la tabla en la base de datos
     * @return Collection<int, TModel> 
     */
    public function all(array $columns=['*']):Collection
    {
        return $this->query()->get($columns);
    }

    /**
     * Crea un nuevo registro en la base de datos.
     *
     * @param array<string, mixed> $data
     * @return TModel
     */
    public function create(array $data):mixed{
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
    public function uptade(int | string $id,array $data):bool{
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
     * Obtiene los resultados paginados.
     *
     * @param int $perPage indica la cantidad de registros que se necesitan, 
     * si no se envia una cantidad desde el front por defecto se enviaran 10 registros.
     * @param array<int, string> $columns indica los campos que se necesitan 
     * @return LengthAwarePaginator<TModel> retorna la data pagina + la informacion de la paginiacion
     * total de registros, la pagina actual, la ultima pagina disponible, etc.
     */
    public function paginate(int $perPage=10, $columns=['*']):LengthAwarePaginator{
        return $this->query()->paginate($perPage,$columns);
    }

}
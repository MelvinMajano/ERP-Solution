<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class ProductsFilterQueryDTO
{
    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(
        public int $page =1,
        public int $pageSize =10,
        public ?string $sortBy ='id',
        public ?string $sortDir ='asc',
        public array $filters =[],
    )
    {}

    public static function fromValidatedData(array $validatedData):self
    {
        //Se extraen de los paramatros validados los datos de relacionados a la paginacion de variables independientes
        $page = (int) ($validatedData['page']??1);
        $pageSize = (int) ($validatedData['pageSize']??10);
        $sortBy = isset($validatedData['sortBy'])? (string) $validatedData['sortBy']:'id';
        $sortDir = isset($validatedData['sortDir'])? (string) $validatedData['sortDir']:'asc';

        //Se eliminan esos datos una vez se extrajo la data de paginacion
        unset($validatedData['page'],$validatedData['pageSize'],$validatedData['sortBy'],$validatedData['sortDir']);

        /**
         * El resto de los paramatros validados se almacenan en un variable de nomida filters
         * los cuales reprenseta los paramatros de filtrados que viene en el $request
        */ 
        $filters=array_filter($validatedData, static fn($val)=>$val !== null && $val !== '');

    /**
     * Crea una nueva instancia de la clase con los parámetros especificados.
     *
     * @param int $page Número de la página actual.
     * @param int $pageSize Cantidad de registros por página.
     * @param string|null $sortBy Campo por el cual ordenar.
     * @param string $sortDir Dirección del ordenamiento (ASC/DESC).
     * @param array $filters Filtros aplicados a la consulta.
     * 
     * @return self Nueva instancia.
     */
        return new self(
            page:$page,
            pageSize:$pageSize,
            sortBy:$sortBy,
            sortDir:$sortDir,
            filters:$filters
        );
    }

    /**
     * Convierte el objeto de paginación/filtrado en un array asociativo.
     *
     * @return array<string, mixed> Estructura con parámetros de paginación, orden y filtros.
     */
    public  function toArray():array
    {
        return[
            'page'=>$this->page,
            'pageSize'=>$this->pageSize,
            'sortBy'=>$this->sortBy,
            'sortDir'=>$this->sortDir,
            'filters'=>$this->filters,
        ];
    }
}
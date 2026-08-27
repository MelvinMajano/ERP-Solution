<?php

namespace Modules\Inventory\Repositories;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Entities\Product;
use Infrastructure\Base\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Columnas que estan permitidas para poder ordenar a los productos
     *
     * @var array<int, string>
     */
    protected array $sortableColumns = [
        'id', 
        'sku', 
        'name', 
        'price', 
        'cost', 
        'current_stock', 
        'created_at'
    ];

    /**
     * Columnas o compos por las se podra realizar una busqueda parcial
     * en caso de que el campo del filtro no est dentro de este array se considerara una 
     * busqueda exacta.
     *
     * @var array<int, string>
     */
    protected array $likeColumns = ['sku', 'name', 'barcode'];

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Busca un producto por su ID en el Tenant activo.
     */
    public function findById(int|string $id): ?Product
    {
        /** @var Product|null */
        return parent::findById($id);
    }

    /**
     * Busca un producto por su código SKU.
     */
    public function findBySku(string $sku): ?Product
    {
        /** @var Product|null */
        return $this->query()->where('sku', $sku)->first();
    }

    /**
     * Verifica si el SKU ya existe dentro del Tenant activo.
     */
    public function isSkuExists(string $sku, int|string|null $ignoreId = null): bool
    {
        $query = $this->query()->where('sku', $sku);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Crea un producto;
     */
    public function create(array $data): Product
    {
        /** @var Product */
        return parent::create($data);
    }  
}
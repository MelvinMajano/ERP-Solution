<?php

namespace Domain\Contracts;

use Domain\Entities\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
/**
 * Esta interfaz tiene el objetivo de complementar al baseRepository
 * pero como ese generico, el @ProductRepositoryInterface le da la especificidad
 * necesaria que requiere el producto.
 */
interface ProductRepositoryInterface
{
    /**
     * Busca un producto por su ID en el Tenant activo.
     */
    public function findById(int |string $id):?Product;
    /**
     * Busca un producto por su SKU exacto.
     */
    public function findBySKU(string $sku):?Product;
    /**
     * Verifica si un SKU ya existe en la base de datos (con opción de ignorar un ID en actualizaciones).
     */
    public function isSkuExists(string $sku, int | string | null $ignoredId =null):bool;
    /**
     * Obtiene la lista paginada y filtrada de productos.
     *
     * @param array<string, mixed> $params
     */
    public function all(array $params=[]):LengthAwarePaginator;
    /**
     * Registra un nuevo producto.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data):Product;
    /**
     * Actualiza un producto por su ID.
     *
     * @param array<string, mixed> $data
     */
    public function update(int | string $id,array $data):bool;
    /**
     * Elimina un producto por su ID.
     */
    public function delete(int | string $id):bool;
    /**
     * Cambia el estado activo/inactivo de un producto.
     */
    public function setStatus(int|string $id, bool $isActive):bool;
}
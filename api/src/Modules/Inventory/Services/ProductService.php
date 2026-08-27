<?php

namespace Modules\Inventory\Services;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Entities\Product;
use Domain\Exceptions\DomainException;
use Domain\DomainServices\ProductDomainService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseService;

class ProductService extends BaseService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductDomainService $productDomainService
    ) {}

    /**
     * Envia la data paginada al controlador
     */
    public function get(array $params = []): LengthAwarePaginator
    {
        return $this->productRepository->all($params);
    }

    /**
     * Obtiene un producto por su id y lo retorna al controller
     */
    public function getById(int | string $id): Product
    {       
        // se obtiene el producto mediante productRepository->findById
        $product = $this->productRepository->findById($id);

        // se valida si el producto existe
        if (!$product) {
            throw new DomainException("El Producto solicitado no existe.");
        }

        //se retorna el producto si este existe
        return $product;
    }

    //Crea un nuevo producto y lo retorna al controller
    public function createProduct(array $data): Product
    {   
        $sku = $data['sku'] ?? null;
        //se valida de que el sku no sea null
        if ($sku !== null) {
        /**
         * Se valida que el sku se unico solo de esa manera se podra crear el producto
         */
            $this->productDomainService->validateUniqueSku($sku);
        }

        //Se crea el producto y se retorna
        return $this->productRepository->create($data);
    }

    //Actualiza un producto y retorna el producto actualizado 
    public function updateProduct(int | string $id, array $data): Product
    {
        //Se obtiene el producto que se intenta acutalizar por medio de su id
        $product = $this->getById($id);

        $sku = $data['sku'] ?? null;

        /**
         * Se valida de que el sku no se nulo e igual que valor que ya existe en el producto
         *  y de ser asi se valida de que se valida de que ese sku no pertenezca a otro producto
         */
        if ($sku !== null && $sku !== $product->sku) {
            $this->productDomainService->validateUniqueSku($sku, $id);
        }

        //se actualiza el producto
        $this->productRepository->update($id, $data);

        // Se retorna el producto actualizado
        return $this->getById($id);
    }
    /**
     * Actualiza el estado del producto y le retorna una repuesta al controller
     * "true" si se actualizo y "false" si no
     */
    public function setStatus(int | string $id, bool $isActive): Product
    {
        //Se obtiene el producto que se intenta cambiar su estado por medio de su id
        $this->getById($id);
        //Se la respuesta 
        $this->productRepository->setStatus($id, $isActive);
        //Retorna la entidad con su nuevo estado 
        return $this->getById($id);
    }

    /**
     * Elimina un producto y retorna una repuesta al controller
     * "true" si el producto se elimino y "false" si sucedio
     */
    public function deleteProduct(int |string $id): bool
    {
        $this->getById($id);
        return $this->productRepository->delete($id);
    }
}

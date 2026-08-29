<?php

namespace Modules\Inventory\Services;

use Domain\Contracts\ProductRepositoryInterface;
use Domain\Entities\Product;
use Domain\Exceptions\DomainException;
use Domain\DomainServices\ProductDomainService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseService;
use Modules\Inventory\DTOs\ProductsDtos\CreateProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\DeleteProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\GetProductByIdDTO;
use Modules\Inventory\DTOs\ProductsDtos\ProductsFilterQueryDTO;
use Modules\Inventory\DTOs\ProductsDtos\SetStatusProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\UpdateProductDTO;

class ProductService extends BaseService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductDomainService $productDomainService
    ) {}

    /**
     * Envia la data paginada al controlador
     */
    public function get(ProductsFilterQueryDTO $dto): LengthAwarePaginator
    {
        return $this->productRepository->all($dto->toArray());
    }

    /**
     * Obtiene un producto por su id y lo retorna al controller
     */
    public function getById(GetProductByIdDTO $dto): Product
    {       
        // se obtiene el producto mediante productRepository->findById
        $product = $this->productRepository->findById($dto->id);

        // se valida si el producto existe
        if (!$product) {
            throw new DomainException("El Producto solicitado no existe.");
        }

        //se retorna el producto si este existe
        return $product;
    }

    //Crea un nuevo producto y lo retorna al controller
    public function createProduct(CreateProductDTO $dto): Product
    {   
        //se valida de que el sku no sea null
        if ($dto->sku !== '') {
        /**
         * Se valida que el sku se unico solo de esa manera se podra crear el producto
         */
            $this->productDomainService->validateUniqueSku($dto->sku);
        }

        //Se crea el producto y se retorna
        return $this->productRepository->create($dto->toArray());
    }

    //Actualiza un producto y retorna el producto actualizado 
    public function updateProduct(UpdateProductDTO $dto): Product
    {
        //Se obtiene el producto que se intenta acutalizar por medio de su id
        $product = $this->getById(new GetProductByIdDTO($dto->id));
        /**
         * Se valida de que el sku no se nulo e igual que valor que ya existe en el producto
         *  y de ser asi se valida de que se valida de que ese sku no pertenezca a otro producto
         */
        if ($dto->sku !== null && $dto->sku !== $product->sku) {
            $this->productDomainService->validateUniqueSku($dto->sku,$dto->id);
        }

        //se actualiza el producto
        $this->productRepository->update($dto->id, $dto->toArray());

        // Se retorna el producto actualizado
        return $this->getById(new GetProductByIdDTO($dto->id));
    }
    /**
     * Actualiza el estado del producto y le retorna el producto
     * con su nuevo estado
     */
    public function setStatus(SetStatusProductDTO $dto): Product
    {
        //Se obtiene el producto que se intenta cambiar su estado por medio de su id
        $this->getById(new GetProductByIdDTO($dto->id));
        //Se la respuesta 
        $this->productRepository->setStatus($dto->id,$dto->isActive);
        //Retorna la entidad con su nuevo estado 
        return $this->getById(new GetProductByIdDTO($dto->id));
    }

    /**
     * Elimina un producto y retorna un valor booleano indicando el resultado
     */
    public function deleteProduct(DeleteProductDTO $dto): bool
    {
        $this->getById(new GetProductByIdDTO($dto->id));
        return $this->productRepository->delete($dto->id);
    }
}

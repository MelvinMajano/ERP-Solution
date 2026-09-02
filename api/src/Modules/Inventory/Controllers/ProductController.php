<?php

namespace Modules\Inventory\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Illuminate\Pagination\Paginator;
use Infrastrucure\Base\BaseController;
use Modules\Inventory\DTOs\ProductsDtos\CreateProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\DeleteProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\GetProductByIdDTO;
use Modules\Inventory\DTOs\ProductsDtos\ProductsFilterQueryDTO;
use Modules\Inventory\DTOs\ProductsDtos\SetStatusProductDTO;
use Modules\Inventory\DTOs\ProductsDtos\UpdateProductDTO;
use Modules\Inventory\Services\ProductService;
use Modules\Inventory\Transformers\ProductTransformer;
use Modules\Inventory\Validators\ProductValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductTransformer $productTransformer
    )
    {}
    public function get(Request $request, Response $response):Response
    {
        //Se obtienen los queryParams
        $queryParams = $request->getQueryParams();
        //Se valida la data
        $validatedData = ProductValidator::getValidation($queryParams);
        //Se crea la estructura de datos de los filtros
        $filterDto = ProductsFilterQueryDTO::fromValidatedData($validatedData);

        /**
         * Se envia la estructura al servicio, eso nos permite tener un mayor control
         * y tipado sobre los paramatros de filtrado y asi abtener la data, paginada y fultrada
         */
        $paginatedData = $this->productService->get($filterDto);

        //Se retorna la respuesta(Data + info de paginacionen formato json
        return $this->paginatedResponse(
            response:$response,
            paginator:$paginatedData,
            transformer: $this->productTransformer,
            message:'Productos obtenidos con exito',
        );
    }

    public function getById(Request $request, Response $response, array $args):Response
    {
        //se obtiene el id desde los argumentos 
        $id = (int) $args['id'];

        //Se valida la data
        $validatedData = ProductValidator::getByIdValidation($id);
        //Se crea la dto de getProductById 
        $dto = GetProductByIdDTO::fromValidatedData($validatedData);

        //Se envia dicha estructura(dto) y se optiene el producto
        $product = $this->productService->getById($dto);

        //Se envia la repuesta en formato json al front
        return $this->jsonResponse(
            response:$response,
                    //Se transforma la entidad, osea el producto en array asociativo
            data: $this->productTransformer->transform($product),
            message: 'Producto obtenido con exito',
        );
    }

    public function create(Request $request, Response $response):Response
    {   
        //Se obtiene la data desde el body de la request
        $body = $request->getParsedBody();
        //Se valida la data
        $validatedData = ProductValidator::createValidation($body);
        //Se crea el dto de el createProduct a partir de la data validada
        $createDto = CreateProductDTO::fromValidatedData($validatedData);
        //Se crea el producto 
        $product = $this->productService->createProduct($createDto);

        //Se envia la repuesta en formato json al front
        return $this->jsonResponse(
            response:$response,
                     //Se transforma la entidad, osea el producto en array asociativo
            data: $this->productTransformer->transform($product),
            message: 'Producto creado con exito',
            statusCode: StatusCodeInterface::STATUS_CREATED
        );
    }

    public function update(Request $request, Response $response, array $args):Response
    {
        //Se obtiene el id desde los arguments
        $id = (int) $args['id'];
        //Se obtiene el body de la rquest
        $body = (array) $request->getParsedBody();

        //Se valida la data, junto con el id
        $validatedData = ProductValidator::updateValidation($id, $body);
        //Se crea el dto del updateProduct a partir de la data validada
        $updateDto = UpdateProductDTO::fromValidatedData($validatedData);

        //Se obtiene el producto con la data actualizada
        $product = $this->productService->updateProduct($updateDto);

        //Se envia la repuesta en formato json al front
        return $this->jsonResponse(
            response:$response,
                     //Se transforma la entidad, osea el producto en array asociativo
            data: $this->productTransformer->transform($product),
            message: 'Proudcto actualizado existosamente'
        );
    }

    public function setStatus(Request $resquest, Response $response, array $args):Response
    {
        //Se obtiene el id desde los arguments
        $id =(int) $args['id'];
        //Se obtiene el body de la request
        $body = (array) $resquest->getParsedBody();

        //Se valida la data, junto con el id
        $validatedData = ProductValidator::setStatusValidation($id,$body);
        //Se crea el dto del setStatus a partir de la data validada
        $setStatusDto = SetStatusProductDTO::fromValidatedData($validatedData);
        //Se obtiene el producto con la data actualizada(El estado actualizado)
        $product = $this->productService->setStatus($setStatusDto);

        //Se envia la repuesta en formato json al front
        return $this->jsonResponse(
            response:$response,
                    //Se transforma la entidad, osea el producto en array asociativo
            data: $this->productTransformer->transform($product),
            message:'Estado del producto actualizado correctamente',
        );
    }

    public function delete(Request $request, Response $response, array $args):Response
    {
        //Se obtiene el id desde los arguments
        $id= (int) $args['id'];
        
        //Se valida la data, junto con el id
        $validatedData = ProductValidator::deleteValidation($id);
         //Se crea el dto del deleteProduct a partir de la data validada
        $deleteDto = DeleteProductDTO::fromValidatedData($validatedData);
        //Se elimina el producto
        $this->productService->deleteProduct($deleteDto);

         //Se envia la repuesta en formato json al front
        return $this->jsonResponse(
            response:$response,
            data: null,
            message: 'Product eliminado con exito'
        );
    }
}
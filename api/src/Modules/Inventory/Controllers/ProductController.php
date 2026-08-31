<?php

namespace Modules\Inventory\Controllers;

use Infrastrucure\Base\BaseController;
use Modules\Inventory\DTOs\ProductsDtos\GetProductByIdDTO;
use Modules\Inventory\DTOs\ProductsDtos\ProductsFilterQueryDTO;
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
        $queryParams = $request->getQueryParams();
        $validatedData = ProductValidator::getValidation($queryParams);

        $filterDto = ProductsFilterQueryDTO::fromValidatedData($validatedData);

        $paginatedData = $this->productService->get($filterDto);

        return $this->paginatedResponse(
            response:$response,
            paginator:$paginatedData,
            transformer: $this->productTransformer,
            message:'Productos obtenidos con exito',
        );
    }

    public function getById(Request $request, Response $response, array $args):Response
    {
        $id = (int) $args['id'];

        $validatedData = ProductValidator::getByIdValidation($id);
        $dto = GetProductByIdDTO::fromValidatedData($validatedData);

        $product = $this->productService->getById($dto);

        return $this->jsonResponse(
            response:$response,
            data: $this->productTransformer->transform($product),
            message: 'Producto obtenido con exito',
        );
    }
}
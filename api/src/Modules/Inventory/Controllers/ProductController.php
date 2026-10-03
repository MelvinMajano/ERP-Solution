<?php

namespace Modules\Inventory\Controllers;

use Fig\Http\Message\StatusCodeInterface;
use Infrastructure\Base\BaseController;
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

/**
 * Controlador HTTP encargado de gestionar las solicitudes y respuestas del recurso Productos.
 * 
 * Intercepta las peticiones de la API, orquesta la validación de la entrada mediante
 * `ProductValidator`, construye los DTOs correspondientes y transforma los resultados
 * devueltos por la capa de servicio.
 *
 * @package Modules\Inventory\Controllers
 */
class ProductController extends BaseController
{
    /**
     * Inicializa el controlador inyectando sus dependencias principales.
     *
     * @param ProductService $productService Servicio de aplicación para la gestión de productos.
     * @param ProductTransformer $productTransformer Transformador para formatear la salida del recurso.
     */
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductTransformer $productTransformer
    ) {}

    /**
     * Obtiene una lista paginada y filtrada de productos.
     *
     * GET /api/v1/products
     *
     * @param Request $request Petición HTTP entrante con los parámetros de consulta (queryParams).
     * @param Response $response Respuesta HTTP actual.
     * @return Response Respuesta HTTP en formato JSON con los productos paginados.
     */
    public function get(Request $request, Response$response): Response
    {
        // 1. Validar los parámetros de consulta paginados vía BaseValidator
        $validatedData = ProductValidator::validatePagination($request->getQueryParams());

        // 2. Construir el DTO de filtrado estructurado
        $filterDto = ProductsFilterQueryDTO::fromValidatedData($validatedData);

        // 3. Ejecutar la consulta paginada en la capa de servicio
        $paginatedData = $this->productService->get($filterDto);

        // 4. Retornar respuesta formateada con metadatos de paginación
        return $this->paginatedResponse(
            response: $response,
            paginator: $paginatedData,
            transformer: $this->productTransformer,
            message: 'Productos obtenidos con éxito'
        );
    }

    /**
     * Obtiene la información detallada de un producto específico mediante su ID.
     *
     * GET /api/v1/products/{id}
     *
     * @param Request $request Petición HTTP entrante.
     * @param Response $response Respuesta HTTP actual.
     * @param array<string, string> $args Argumentos extraídos de los parámetros de ruta (ej. 'id').
     * @return Response Respuesta HTTP en formato JSON con la entidad transformada.
     */
    public function getById(Request $request, Response $response, array$args): Response
    {
        // 1. Validar la integridad del ID recibido en la ruta
        $validatedData = ProductValidator::validateId((int)$args['id']);

        // 2. Mapear los datos validados al DTO específico
        $dto = GetProductByIdDTO::fromValidatedData($validatedData);

        // 3. Obtener la entidad de producto
        $product = $this->productService->getById($dto);

        // 4. Retornar la respuesta JSON
        return $this->jsonResponse(
            response: $response,
            data: $this->productTransformer->transform($product),
            message: 'Producto obtenido con éxito'
        );
    }

    /**
     * Procesa la creación de un nuevo producto en el sistema.
     *
     * POST /api/v1/products
     *
     * @param Request $request Petición HTTP entrante con los datos del nuevo producto en el body.
     * @param Response $response Respuesta HTTP actual.
     * @return Response Respuesta HTTP en formato JSON con el producto creado y estado 201 Created.
     */
    public function create(Request $request, Response$response): Response
    {
        // 1. Extraer y validar el payload de la petición
        $body = (array)$request->getParsedBody();
        $validatedData = ProductValidator::createValidation($body);

        // 2. Inyectar el ID del usuario autenticado proveniente de la sesión/middleware
        $userId = (int)$request->getAttribute('user_id');
        $validatedData['created_by'] =$userId;

        // 3. Construir el DTO y ejecutar el servicio de creación
        $createDto = CreateProductDTO::fromValidatedData($validatedData);$product = $this->productService->createProduct($createDto);

        // 4. Retornar respuesta JSON de recurso creado
        return $this->jsonResponse(
            response: $response,
            data: $this->productTransformer->transform($product),
            message: 'Producto creado con éxito',
            statusCode: StatusCodeInterface::STATUS_CREATED
        );
    }

    /**
     * Procesa la actualización parcial o total de los datos de un producto existente.
     *
     * PUT /api/v1/products/{id}
     *
     * @param Request $request Petición HTTP entrante con los campos a modificar.
     * @param Response $response Respuesta HTTP actual.
     * @param array<string, string> $args Argumentos de la ruta (contiene 'id').
     * @return Response Respuesta HTTP en formato JSON con el producto actualizado.
     */
    public function update(Request $request, Response $response, array$args): Response
    {
        // 1. Combinar el ID de la ruta con el cuerpo del request y validar
        $id = (int) $args['id'];$body = (array) $request->getParsedBody();$validatedData = ProductValidator::updateValidation($id,$body);

        // 2. Construir DTO y procesar la actualización
        $updateDto = UpdateProductDTO::fromValidatedData($validatedData);$product = $this->productService->updateProduct($updateDto);

        // 3. Retornar respuesta con los datos transformados
        return $this->jsonResponse(
            response: $response,
            data: $this->productTransformer->transform($product),
            message: 'Producto actualizado exitosamente'
        );
    }

    /**
     * Procesa la modificación del estado operativo (activo/inactivo) de un producto.
     *
     * PATCH /api/v1/products/{id}/status
     *
     * @param Request $request Petición HTTP entrante con el nuevo estado ('is_active').
     * @param Response $response Respuesta HTTP actual.
     * @param array<string, string> $args Argumentos de la ruta (contiene 'id').
     * @return Response Respuesta HTTP en formato JSON con el producto y su nuevo estado.
     */
    public function setStatus(Request $request, Response $response, array$args): Response
    {
        // 1. Validar el ID y la propiedad 'is_active' mediante BaseValidator
        $id = (int) $args['id'];$body = (array) $request->getParsedBody();$validatedData = ProductValidator::validateStatus($id,$body);

        // 2. Crear DTO y actualizar el estado
        $setStatusDto = SetStatusProductDTO::fromValidatedData($validatedData);$product = $this->productService->setStatus($setStatusDto);

        // 3. Retornar respuesta
        return $this->jsonResponse(
            response: $response,
            data: $this->productTransformer->transform($product),
            message: 'Estado del producto actualizado correctamente'
        );
    }

    /**
     * Procesa la eliminación física o lógica de un registro de producto por ID.
     *
     * DELETE /api/v1/products/{id}
     *
     * @param Request $request Petición HTTP entrante.
     * @param Response $response Respuesta HTTP actual.
     * @param array<string, string> $args Argumentos de la ruta (contiene 'id').
     * @return Response Respuesta HTTP en formato JSON confirmando la eliminación.
     */
    public function delete(Request $request, Response $response, array$args): Response
    {
        // 1. Validar el ID de la entidad a eliminar
        $validatedData = ProductValidator::validateId((int)$args['id']);

        // 2. Crear el DTO y proceder con la eliminación en el servicio
        $deleteDto = DeleteProductDTO::fromValidatedData($validatedData);
        $this->productService->deleteProduct($deleteDto);

        // 3. Retornar respuesta JSON limpia sin payload de datos
        return $this->jsonResponse(
            response: $response,
            data: null,
            message: 'Producto eliminado con éxito'
        );
    }
}
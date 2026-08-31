<?php

namespace Infrastrucure\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Infrastructure\Base\BaseTransformer;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    //Retorna una respuesta en formato JSON
    protected function jsonResponse(
        Response $response,
        mixed $data=null,
        string $message = 'Operacion realizada con exito',
        int $statusCode=200,
        array $errors=[],
        array $meta = []
    ):Response{
        $payload=[
            'status'=>$statusCode>=200 && $statusCode<300?'success':'error',
            'message'=>$message,
            'data'=>$data,
            'errors'=>$errors
        ];

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        $json= json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($json);

        return $response
               ->withHeader('Content-Type','application/json')
               ->withStatus($statusCode);
    }

    protected function paginatedResponse(
        Response $response,
        LengthAwarePaginator $paginator,
        BaseTransformer $transformer,
        string $message = 'Datos obtenidos con exito',
        int $statusCode = 200,
    ):Response{
        $data = $transformer->transformCollection($paginator->items());

        $meta = [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ];

        return $this->jsonResponse(
            response: $response,
            data: $data,
            message: $message,
            statusCode: $statusCode,
            meta: $meta
        );
    }
}
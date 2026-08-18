<?php

namespace Infrastrucure\Base;

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
    ):Response{
        $payload=[
            'status'=>$statusCode>=200 && $statusCode<300?'success':'error',
            'message'=>$message,
            'data'=>$data,
            'errors'=>$errors
        ];

        $json= json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($json);

        return $response
               ->withHeader('Content-Type','application/json')
               ->withStatus($statusCode);
    }

}
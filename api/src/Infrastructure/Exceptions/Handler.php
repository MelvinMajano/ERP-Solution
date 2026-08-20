<?php

namespace Infrastructure\Exceptions;

use Domain\Exceptions\DomainException;
use Illuminate\Database\QueryException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class Handler implements ErrorHandlerInterface
{
    public function __invoke(
        ServerRequestInterface $request, 
        Throwable $exception, 
        bool $displayErrorDetails, 
        bool $logErrors, 
        bool $logErrorDetails): Response
    {
       $statusCode =500;
       $message = "Ha ocurrido un error interno en el servidor.";
       $errors=[];

       if($exception instanceof DomainException){
            $statusCode = 422;
            $message = $exception->getMessage();
            $errors = $exception->getErrors();
       }
       elseif($exception instanceof InfrastructureException)
       {
        $statusCode = $exception->getStatuCode();
        $message = $exception->getMessage();
        $errors = $exception->getErrors();
       }
       elseif ($exception instanceof HttpException) {
        $statusCode=$exception->getCode();
        $message=$exception->getMessage();
       }
       elseif ($exception instanceof QueryException) {
        $statusCode = 400;
        $message = 'Error en la consulta de base de datos';

        if($displayErrorDetails){
            $errors['sql_message']=$exception->getMessage();
        }
       }
       else{
        $code = $exception->getCode();
        if(is_int($code) && $code >= 100 && $code <=599){
            $statusCode = $code;
        }
        $message = $displayErrorDetails ? $exception->getMessage() : 'Error interno del servidor.';
       }

       if (!is_int($statusCode) || $statusCode < 100 || $statusCode > 599) {
            $statusCode = 500;
        }

       $payload =[
            'status'=> $statusCode >= 200 && $statusCode<300 ? 'success':'error',
            'message'=>$message,
            'data'=>null,
            'errors'=>$errors,
       ];

       $response = new SlimResponse();
       $json = json_encode($payload);
       return $response;
    }
}
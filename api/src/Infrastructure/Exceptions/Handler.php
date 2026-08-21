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

/**
 * Manejador global de excepciones.
 */
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

       /**
        * Validacion de excepciones de dominio, estan son las 
        * excepciones de la logica de negocio.
        */
       if($exception instanceof DomainException){
            $statusCode = 422;
            $message = $exception->getMessage();
            $errors = $exception->getErrors();
       }
       /**
        * Validacion de excepciones de infrastructure, excepciones tales como
        * No autroizado, o recurso no encotrado, etc.
        */
       elseif($exception instanceof InfrastructureException)
       {
        $statusCode = $exception->getStatuCode();
        $message = $exception->getMessage();
        $errors = $exception->getErrors();
       }
       /**
        * Validacion de excepciones del protocolo Http, 
        * Excepciones nativas de Slim (Rutas no encontradas, métodos no permitidos)
        */
       elseif ($exception instanceof HttpException) {
        $statusCode=$exception->getCode();
        $message=$exception->getMessage();
       }
       // Excepciones de base de datos (QueryBuilder / Eloquent)
       elseif ($exception instanceof QueryException) {
        $statusCode = 400;
        $message = 'Error en la consulta de base de datos';

        if($displayErrorDetails){
            $errors['sql_message']=$exception->getMessage();
        }
       }
       // Excepciones no controladas de PHP
       else{
        $code = $exception->getCode();
        if(is_int($code) && $code >= 100 && $code <=599){
            $statusCode = $code;
        }
        $message = $displayErrorDetails ? $exception->getMessage() : 'Error interno del servidor.';
       }

       // Asegurar status code HTTP válido
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
<?php

namespace Infrastructure\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
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

       }
       
       elseif($exception instanceof InfrastructureException)
       {
        $statusCode = $exception->getStatuCode();
        $message = $exception->getMessage();
        $errors = $exception->getErrors();
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
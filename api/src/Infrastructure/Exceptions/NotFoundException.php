<?php

namespace Infrastructure\Exceptions;

use Fig\Http\Message\StatusCodeInterface;

//Tiene como objetivo ser la excepcion que maneje cuando un recurso no se encuentra 
class NotFoundException extends InfrastructureException
{
    public function __construct(
        string $message = "Recurso no encontrado",
        array $errors =[]
    ){
        parent::__construct($message,StatusCodeInterface::STATUS_NOT_FOUND,$errors);
    }
}
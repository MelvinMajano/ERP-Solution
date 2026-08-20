<?php

namespace Infrastructure\Exceptions;

use Fig\Http\Message\StatusCodeInterface;

/**
 * Tiene como objetivo ser la excepcion que maneje el caso de cuando un 
 * usuario que no tiene autorizacion quiere acceder a un recurso que no le
 * es permitido
 */
class UnauthorizedException extends InfrastructureException
{
    public function __construct(
        string $message = "No autorizado",
        array $errors = []
    ){
        parent::__construct($message,StatusCodeInterface::STATUS_UNAUTHORIZED,$errors);
    }
}
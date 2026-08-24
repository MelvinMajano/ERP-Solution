<?php

namespace Infrastructure\Exceptions;

use Fig\Http\Message\StatusCodeInterface;
//Se laza cuando hay algun error en los datos obtenidos de la request.
class ValidationException extends InfrastructureException
{
    public function __construct(
        string $message = "Ha ocurrido un error al validar la data.", 
        array $errors = [])
    {
        parent::__construct($message,StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $errors);
    }
}
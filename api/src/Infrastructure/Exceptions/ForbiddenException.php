<?php

namespace Infrastructure\Exceptions;

use Fig\Http\Message\StatusCodeInterface;

//Tiene como objetivo ser la excepcion que controla la restriccion de acceso
class ForbiddenException extends InfrastructureException
{
    public function __construct(
        string $message = "Acceso Prohibido",
        protected array $errors = []
    )
    {
        parent::__construct($message,StatusCodeInterface::STATUS_FORBIDDEN, $errors);
    }
}
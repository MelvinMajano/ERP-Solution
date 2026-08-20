<?php

namespace Infrastructure\Exceptions;
 
use Exception;
/**
 * Centraliza las excepciones de infrastructura
 */
abstract class InfrastructureException extends Exception
{
    //Inicializa la excepcion de infrastructura
    public function __construct(        string $message,
        protected int $statusCode = 500,
        protected array $errors=[],
    ){
        parent::__construct($message);
    }

    //Permite obtner el Codigo de estado a las de las excepciones que extiendan de InfrastructureException
    public function getStatuCode():int
    {
        return $this->statusCode;
    }
    //Permite obtner el detalle de los errores a las de las excepciones que extiendan de InfrastructureException
    public function getErrors():array
    {
        return $this->errors;
    }
}
<?php

namespace Domain\Exceptions;

use Exception;

/**
 * El proposito de esta clase es centralizar las exepciones de la logica de negocio
 * del sistema 
 */
abstract class DomainException extends Exception
{   
    //Inicializa la excepcion del dominio
    public function __construct(
        string $message,
        protected array $errors = []
    )
    {
        parent::__construct($message);
    }

    /**
     * permite recuperar el detalle de los errores desde las clases que 
     * que extiendan a DomianException
    */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
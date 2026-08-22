<?php 

namespace Infrastructure\Contracts;

interface ServiceInterface
{
    //Firma genérica para la ejecución de casos de uso.
    public function execute(array $data=[]):mixed;
}
<?php

namespace Infrastructure\Base;


abstract class BaseTransformer{

    /**
     * Esta funcion sirve como una interfaz donde entra un tipo de dato
     * y retorna un array
     */
    abstract public function transform(mixed $item):array;
     /**
     * Esta funcion es la que transforma una collecion o lista de entidades
     * a un arreglo utilzando la el transform como interfaz para obtener ese array;
     */
    public function transformCollection(iterable $items):array{
        $result = [];
        foreach($items as $item){
            $result[]=$this->transform($item);
        }
        return $result;
    }
};
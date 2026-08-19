<?php

namespace Infrastrucure\Base;

use Illuminate\Database\Capsule\Manager as DB;

abstract class BaseService{
    /**
     * Este metodo permite ejecutar una transaccion sin necesitda de tener
     * que depender directamente de la tecnologia que la realizara
     * 
     * @template T
     * @param callable(): T $callback
     * @return T
     * @throws \Throwable
     */
    protected function transaction(callable $callback):mixed{
        return DB::transaction();
    }
}
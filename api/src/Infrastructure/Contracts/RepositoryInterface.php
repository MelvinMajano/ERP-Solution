<?php

namespace Infrastructure\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function findById(int | string $id):?Model;

    public function all(array $params=[]):LengthAwarePaginator;

    public function create(array $data):Model;

    public function update(int | string $id,array $data):bool;

    public function delete(int | string $id):bool;
    
    public function setStatus(int | string $id,bool $isActive):bool;
}
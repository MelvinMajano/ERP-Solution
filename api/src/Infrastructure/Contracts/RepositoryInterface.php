<?php

namespace Infrastructure\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function findById(int | string $id):?Model;

    public function all(array $columns=['*']):Collection;

    public function create(array $data):Model;

    public function uptade(int | string $id,array $data):bool;

    public function delete(int | string $id):bool;
    
    public function paginate(int $perPage=10, $columns=['*']):LengthAwarePaginator;
}
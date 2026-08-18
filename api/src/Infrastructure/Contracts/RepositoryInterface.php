<?php

namespace Infrastructure\Contracts;

interface RepositoryInterface
{
    public function findById(int | string $id):mixed;

    public function all(array $columns=['*']):mixed;

    public function create(array $data):mixed;

    public function uptade(int | string $id,array $data):bool;

    public function delete(int | string $id):bool;
    
    public function paginate(int $perPage=10, $columns=['*']):mixed;
}
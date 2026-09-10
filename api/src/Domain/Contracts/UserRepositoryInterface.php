<?php

namespace Domain\Contracts;

use Domain\Entities\User;
use Infrastructure\Contracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function findByUsername(string $username): ?User;
}
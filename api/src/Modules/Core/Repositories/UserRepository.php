<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\UserRepositoryInterface;
use Domain\Entities\User;
use Infrastructure\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()
            ->where('email', strtolower(trim($email)))
            ->first();
    }

    public function findByUsername(string $username): ?User
    {
        return $this->query()
            ->where('username', trim($username))
            ->first();
    }
}
<?php

namespace Modules\Core\Repositories;

use Domain\Contracts\UserRepositoryInterface;
use Domain\Entities\User;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación de persistencia para Usuarios dentro del módulo Core.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Constructor del repositorio.
     *
     * @param User $model Inyección de la entidad User.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|null */
        return $this->query()
            ->where('email', strtolower(trim($email)))
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public function findByUsername(string $username): ?User
    {
        /** @var User|null */
        return $this->query()
            ->where('username', trim($username))
            ->first();
    }
}
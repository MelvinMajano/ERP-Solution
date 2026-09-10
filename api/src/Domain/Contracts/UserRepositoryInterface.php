<?php

namespace Domain\Contracts;

use Domain\Entities\User;
use Infrastructure\Contracts\RepositoryInterface;

/**
 * Contrato de repositorio para la entidad User.
 *
 * Define operaciones de lectura especializadas para la autenticación e inscripciones.
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Busca un usuario por su dirección de correo electrónico dentro del scope activo.
     *
     * @param string $email Correo electrónico a consultar.
     * @return User|null Instancia del usuario o null si no existe.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Busca un usuario por su nombre de usuario.
     *
     * @param string $username Nombre de usuario a consultar.
     * @return User|null Instancia del usuario o null si no existe.
     */
    public function findByUsername(string $username): ?User;
}
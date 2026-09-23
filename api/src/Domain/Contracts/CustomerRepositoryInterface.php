<?php

namespace Domain\Contracts;

use Domain\Entities\Customer;
use Infrastructure\Contracts\RepositoryInterface;

/**
 * Contrato de persistencia para la entidad Customer.
 * Extiende las operaciones CRUD base del repositorio genérico.
 */
interface CustomerRepositoryInterface extends RepositoryInterface
{
    /**
     * Busca un cliente por su ID en el ámbito del Tenant activo.
     */
    public function findById(int|string $id): ?Customer;

    /**
     * Persiste un nuevo cliente en la base de datos.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Customer;
}
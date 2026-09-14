<?php

namespace Modules\CRM\Repositories;

use Domain\Contracts\CustomerRepositoryInterface;
use Domain\Entities\Customer;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación de persistencia para la gestión de clientes mediante Eloquent.
 */
class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    /**
     * Columnas permitidas para el ordenamiento dinámico en consultas paginadas.
     * 
     * @var array<int, string>
     */
    protected array $sortableColumns = ['id', 'name', 'rtn', 'email', 'created_at'];

    /**
     * Columnas sujetas a coincidencias parciales (LIKE) durante el filtrado.
     * 
     * @var array<int, string>
     */
    protected array $likeColumns = ['name', 'rtn', 'email', 'phone'];

    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * {@inheritDoc}
     */
    public function findById(int|string $id): ?Customer
    {
        /** @var Customer|null */
        return parent::findById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): Customer
    {
        /** @var Customer */
        return parent::create($data);
    }
}
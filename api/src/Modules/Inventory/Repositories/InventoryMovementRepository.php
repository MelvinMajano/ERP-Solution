<?php

namespace Modules\Inventory\Repositories;

use Domain\Contracts\InventoryMovementRepositoryInterface;
use Domain\Entities\InventoryMovement;
use Infrastructure\Base\BaseRepository;

/**
 * Implementación concreta del repositorio de Kardex mediante Eloquent.
 */
class InventoryMovementRepository extends BaseRepository implements InventoryMovementRepositoryInterface
{
    protected array $sortableColumns = ['id', 'quantity', 'created_at', 'type', 'unit_cost'];
    protected array $likeColumns = ['reference_type', 'notes'];

    public function __construct(InventoryMovement $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): InventoryMovement
    {
        /** @var InventoryMovement */
        return parent::create($data);
    }

    public function findByProduct(int $productId): array
    {
        return $this->query()
            ->where('product_id', $productId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->all();
    }
}
<?php

declare(strict_types=1);

namespace Modules\Billing\DTOs;

use Domain\ValueObjects\SaleItemInput;

/**
 * Class CreateSaleDTO
 *
 * Objeto de Transferencia de Datos inmutable para la creación de una venta/factura en el módulo Billing.
 *
 * @package Modules\Billing\DTOs
 */
readonly class CreateSaleDTO
{
    /**
     * @param int $customerId Identificador del cliente.
     * @param int $cashierUserId Identificador del usuario emisor/cajero.
     * @param list<SaleItemInput> $items Colección de ítems de la venta.
     * @param int|null $cashBatchId Identificador opcional del lote/turno de caja.
     * @param string|null $notes Notas adicionales o descripción.
     */
    public function __construct(
        public int $customerId,
        public int $cashierUserId,
        /** @var list<SaleItemInput> */
        public array $items,
        public ?int $cashBatchId = null,
        public ?string $notes = null,
    ) {}

    /**
     * Crea una instancia del DTO a partir de un array de datos previamente validados
     * convirtiendo los ítems en Value Objects de Dominio.
     *
     * @param array<string, mixed> $validatedData
     * @return self
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            customerId: (int) $validatedData['customer_id'],
            cashierUserId: (int) $validatedData['cashier_user_id'],
            cashBatchId: isset($validatedData['cash_batch_id']) ? (int) $validatedData['cash_batch_id'] : null,
            notes: isset($validatedData['notes']) ? (string) $validatedData['notes'] : null,
            items: array_map(static fn (array $item): SaleItemInput => new SaleItemInput(
                productId: (int) $item['product_id'],
                productName: (string) ($item['product_name'] ?? ''),
                quantity: (float) $item['quantity'],
                unitPrice: (float) $item['unit_price'],
                unitCost: (float) ($item['unit_cost'] ?? 0.0),
                discountAmount: isset($item['discount']) ? (float) $item['discount'] : 0.0,
                taxRate: isset($item['tax_rate']) ? (float) $item['tax_rate'] : 0.15
            ), $validatedData['items'] ?? [])
        );
    }

    /**
     * Convierte el DTO en un array asociativo excluyendo los valores nulos.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'customer_id'     => $this->customerId,
            'cashier_user_id' => $this->cashierUserId,
            'cash_batch_id'   => $this->cashBatchId,
            'notes'           => $this->notes,
            'items'           => array_map(fn (SaleItemInput $item): array => [
                'product_id' => $item->productId,
                'quantity'   => $item->quantity,
                'unit_price' => $item->unitPrice,
                'discount'   => $item->discountAmount,
            ], $this->items),
        ], static fn($val) => $val !== null);
    }
}
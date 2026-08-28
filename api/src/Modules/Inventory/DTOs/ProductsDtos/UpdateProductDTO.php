<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class UpdateProductDTO
{
    public function __construct(
        public ?string $sku = null,
        public ?string $name = null,
        public ?float $price = null,
        public ?float $cost = null,
        public ?int $primarySupplierId = null,
        public ?string $barcode = null,
        public ?float $currentStock = null,
        public ?bool $isService = null,
        public ?bool $isActive = null,
    ) {}

    /**
     * Crea una instancia a partir de un array de datos validados.
     * 
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData): self
    {   
        return new self(
            sku: isset($validatedData['sku'])?(string) $validatedData['sku']:null,
            name: isset($validatedData['name'])?(string) $validatedData['name']:null,
            price: isset($validatedData['price'])?(float) $validatedData['price']: null,
            cost: isset($validatedData['cost'])?(float) $validatedData['cost']: null,
            primarySupplierId: isset($validatedData['primary_supplier_id']) ? (int) $validatedData['primary_supplier_id'] : null,
            barcode: isset($validatedData['barcode'])?(string) $validatedData['barcode']:  null,
            currentStock:isset($validatedData['current_stock']) ? (float) $validatedData['current_stock'] : null,
            isService:isset($validatedData['is_service']) ? (bool) $validatedData['is_service'] : null,
            isActive:isset($validatedData['is_active']) ? (bool) $validatedData['is_active'] : null,
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
            'primary_supplier_id' => $this->primarySupplierId,
            'sku'                 => $this->sku,
            'barcode'             => $this->barcode,
            'name'                => $this->name,
            'price'               => $this->price,
            'cost'                => $this->cost,
            'current_stock'       => $this->currentStock,
            'is_service'          => $this->isService,
            'is_active'           => $this->isActive,
        ],static fn($val) => $val !== null);
    }
}

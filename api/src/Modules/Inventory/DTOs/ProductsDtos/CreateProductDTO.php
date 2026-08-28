<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class CreateProductDTO
{
    public function __construct(
        public string $sku,
        public string $name,
        public float $price,
        public float $cost,
        public ?int $primarySupplierId = null,
        public ?string $barcode = null,
        public float $currentStock = 0.0,
        public bool $isService = false,
        public bool $isActive = true,
    )
    {}

    /**
     * Crea una instancia a partir de un array de datos validados.
     * 
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData):self
    {
        return new self(
         sku: (string) $validatedData['sku'],
         name: (string) $validatedData['name'],
         price: (float) $validatedData['price'],
         cost: (float) $validatedData['cost'],
         primarySupplierId:isset($validatedData['primary_supplier_id'])?(int) $validatedData['primary_supplier_id']:null,
         barcode: isset($validatedData['barcode'])?(string) $validatedData['barcode']:null,
         currentStock:isset($validatedData['current_stock'])?(float) $validatedData['current_stock']:0.0,
         isService: (bool) ($validatedData['is_service']??false),
         isActive: (bool) ($validatedData['is_active']??false),
        );
    }

    /**
     * Convierte el DTO en un array asociativo excluyendo los valores nulos.
     *
     * @return array<string, mixed> 
     */
    public function toArray():array
    {
        return array_filter([
           'primary_supplier_id' =>  $this->primarySupplierId,
            'sku'                 => $this->sku,
            'barcode'             => $this->barcode,
            'name'                => $this->name,
            'price'               => $this->price,
            'cost'                => $this->cost,
            'current_stock'       => $this->currentStock,
            'is_service'          => $this->isService,
            'is_active'           => $this->isActive, 
        ], static fn($val) => $val !== null);
    }
}
<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class GetProductByIdDT
{
    public function __construct(
        public int $id
    )
    {}

    public static function fromValidatedData(array $validatedData):self
    {
        return new self
        (
            id: (int) $validatedData['id']
        );
    }

    public function toArray():array
    {
        return 
        [
            'id' => $this->id,
        ];
    }
}
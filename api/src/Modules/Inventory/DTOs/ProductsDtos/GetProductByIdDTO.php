<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class GetProductByIdDTO
{
    public function __construct(
        public int $id
    )
    {}

    /**
     * Crea una instancia a partir de un array de datos validados.
     * 
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData):self
    {
        return new self
        (
            id: (int) $validatedData['id']
        );
    }

    /**
     * Convierte el DTO en un array asociativo.
     *
     * @return array<string, mixed> 
     */
    public function toArray():array
    {
        return 
        [
            'id' => $this->id,
        ];
    }
}
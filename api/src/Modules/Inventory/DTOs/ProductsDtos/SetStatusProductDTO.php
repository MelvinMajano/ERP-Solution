<?php

namespace Modules\Inventory\DTOs\ProductsDtos;

readonly class SetStatusProductDTO 
{
    public function __construct(
        public int $id,
        public bool $isActive,
    )
    {}

    /**
     * Crea una instancia a partir de un array de datos validados.
     * 
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData):self{
        return new self(
            id:(int) $validatedData['id'],
            isActive:(bool) $validatedData['is_active'],  
        );
    }

     /**
     * Convierte el DTO en un array asociativo.
     *
     * @return array<string, mixed> 
     */
    public function toArray():array{
        return [
            'id' => $this->id,
            'is_active'           => $this->isActive,
        ];
    }
}
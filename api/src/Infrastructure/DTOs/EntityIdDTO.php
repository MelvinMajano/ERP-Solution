<?php

namespace Infrastructure\DTOs;

/**
 * DTO genérico para transportar la llave primaria procesada en búsquedas y eliminaciones por ID.
 */
readonly class EntityIdDTO
{
    public function __construct(public int $id) {}

    public static function fromValidatedData(array $validatedData): self
    {
        return new self(id: (int) $validatedData['id']);
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
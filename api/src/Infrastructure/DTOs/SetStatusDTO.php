<?php

namespace Infrastructure\DTOs;

/**
 * Objeto inmutable para el transporte de peticiones de cambio de estado activo/inactivo.
 */
readonly class SetStatusDTO
{
    public function __construct(
        public int $id,
        public bool $isActive
    ) {}

    /**
     * Construye una instancia a partir del payload y el ID validados.
     *
     * @param array<string, mixed> $validatedData Arreglo proveniente del BaseValidator::validateStatus.
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            id: (int) $validatedData['id'],
            isActive: (bool) $validatedData['is_active']
        );
    }

    /**
     * Exporta los atributos para la capa de persistencia.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'is_active' => $this->isActive,
        ];
    }
}
<?php

declare(strict_types=1);

namespace Modules\Inventory\DTOs\MovementReasonDtos;

/**
 * Objeto de Transferencia de Datos (DTO) para la actualización de un Motivo de Movimiento.
 *
 * Contiene el identificador y las propiedades opcionales permitidas para
 * realizar una actualización parcial o total del recurso.
 */
readonly class UpdateMovementReasonDTO
{
    /**
     * @param int $id Identificador primario del motivo a actualizar.
     * @param string|null $name Nuevo nombre del motivo (opcional).
     * @param string|null $movementType Nuevo tipo de movimiento 'IN' o 'OUT' (opcional).
     * @param bool|null $isActive Nuevo estado del registro (opcional).
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $movementType = null,
        public ?bool $isActive = null
    ) {}

    /**
     * Instancia el DTO a partir de la estructura de datos procesada por el validador HTTP.
     *
     * @param array<string, mixed> $validatedData Datos validados que incluyen el id del registro.
     * @return self
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            id: (int) $validatedData['id'],
            name: isset($validatedData['name']) ? (string) $validatedData['name'] : null,
            movementType: isset($validatedData['movement_type']) ? strtoupper((string) $validatedData['movement_type']) : null,
            isActive: isset($validatedData['is_active']) ? (bool) $validatedData['is_active'] : null
        );
    }

    /**
     * Convierte las propiedades definidas a un arreglo asociativo filtrando los valores nulos.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id'            => $this->id,
            'name'          => $this->name,
            'movement_type' => $this->movementType,
            'is_active'     => $this->isActive,
        ], static fn($val) => $val !== null);
    }
}
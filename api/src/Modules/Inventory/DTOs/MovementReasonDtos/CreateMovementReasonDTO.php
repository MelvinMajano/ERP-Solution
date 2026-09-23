<?php

namespace Modules\Inventory\DTOs\MovementReasonDtos;

/**
 * Objeto de Transferencia de Datos (DTO) para la creación de un Motivo de Movimiento.
 *
 * Mantiene la inmutabilidad de los datos de entrada requeridos para crear
 * un nuevo registro de motivo de inventario en el sistema.
 */
readonly class CreateMovementReasonDTO
{
    /**
     * @param string $name Nombre descriptivo del motivo (ej: "Venta POS", "Ajuste por Merma").
     * @param string $movementType Dirección del movimiento de stock: 'IN' o 'OUT'.
     * @param bool $isActive Estado inicial de la entidad. Por defecto true.
     */
    public function __construct(
        public string $name,
        public string $movementType,
        public bool $isActive = true
    ) {}

    /**
     * Construye una instancia inmutable del DTO a partir del array de datos validados.
     *
     * @param array<string, mixed> $validatedData Array procesado previamente por el validador HTTP.
     * @return self
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            name: (string) $validatedData['name'],
            movementType: strtoupper((string) $validatedData['movement_type']),
            isActive: (bool) ($validatedData['is_active'] ?? true)
        );
    }

    /**
     * Convierte los atributos del DTO a un arreglo asociativo para la persistencia.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name'          => $this->name,
            'movement_type' => $this->movementType,
            'is_active'     => $this->isActive,
        ], static fn($val) => $val !== null);
    }
}
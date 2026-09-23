<?php

namespace Modules\Billing\DTOs\CustomersDtos;

/**
 * Objeto inmutable para el transporte de datos al crear un cliente.
 */
readonly class CreateCustomerDTO
{
    public function __construct(
        public string $name,
        public ?string $rtn = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?int $createdBy = null,
        public bool $isActive = true
    ) {}

    /**
     * Construye una instancia a partir del arreglo saneado por el validador.
     *
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            name: (string) $validatedData['name'],
            rtn: isset($validatedData['rtn']) ? (string) $validatedData['rtn'] : null,
            email: isset($validatedData['email']) ? (string) $validatedData['email'] : null,
            phone: isset($validatedData['phone']) ? (string) $validatedData['phone'] : null,
            address: isset($validatedData['address']) ? (string) $validatedData['address'] : null,
            createdBy: isset($validatedData['created_by']) ? (int) $validatedData['created_by'] : null,
            isActive: (bool) ($validatedData['is_active'] ?? true)
        );
    }

    /**
     * Convierte el DTO a un arreglo plano excluyendo claves con valor nulo.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'rtn'       => $this->rtn,
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'created_by'=> $this->createdBy,
            'is_active' => $this->isActive,
        ], static fn($val) => $val !== null);
    }
}
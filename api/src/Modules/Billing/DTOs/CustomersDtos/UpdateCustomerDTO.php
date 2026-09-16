<?php

namespace Modules\Billing\DTOs\CustomersDtos;

/**
 * Objeto inmutable para el transporte de datos en actualizaciones de cliente.
 */
readonly class UpdateCustomerDTO
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $rtn = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?bool $isActive = null
    ) {}

    /**
     * Construye una instancia mapeando únicamente los campos enviados en el cuerpo de la petición.
     *
     * @param array<string, mixed> $validatedData
     */
    public static function fromValidatedData(array $validatedData): self
    {
        return new self(
            id: (int) $validatedData['id'],
            name: isset($validatedData['name']) ? (string) $validatedData['name'] : null,
            rtn: isset($validatedData['rtn']) ? (string) $validatedData['rtn'] : null,
            email: isset($validatedData['email']) ? (string) $validatedData['email'] : null,
            phone: isset($validatedData['phone']) ? (string) $validatedData['phone'] : null,
            address: isset($validatedData['address']) ? (string) $validatedData['address'] : null,
            isActive: isset($validatedData['is_active']) ? (bool) $validatedData['is_active'] : null
        );
    }

    /**
     * Prepara el arreglo de actualización filtrando propiedades omitidas (null).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id'        => $this->id,
            'rtn'       => $this->rtn,
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'is_active' => $this->isActive,
        ], static fn($val) => $val !== null);
    }
}
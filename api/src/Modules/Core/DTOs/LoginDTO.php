<?php

namespace Modules\Core\DTOs;

/**
 * Objeto de Transferencia de Datos (DTO) inmutable para el flujo de autenticación.
 */
readonly class LoginDTO
{
    public function __construct(
        public ?string $email = null,
        public ?int $tenantId = null,
        public ?string $password = null
    ) {}

    /**
     * Construye una instancia a partir de los datos validados del Paso 1 (check-email).
     *
     * @param array<string, mixed> $validatedData
     * @return self
     */
    public static function fromCheckEmail(array $validatedData): self
    {
        return new self(
            email: strtolower(trim((string) $validatedData['email']))
        );
    }

    /**
     * Construye una instancia a partir de los datos validados del Paso 2 (login-password).
     *
     * @param array<string, mixed> $validatedData
     * @return self
     */
    public static function fromPassword(array $validatedData): self
    {
        return new self(
            tenantId: (int) $validatedData['tenant_id'],
            password: (string) $validatedData['password']
        );
    }

    /**
     * Retorna los datos como un arreglo asociativo.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'email'     => $this->email,
            'tenant_id' => $this->tenantId,
            'password'  => $this->password,
        ], fn ($value) => $value !== null);
    }
}
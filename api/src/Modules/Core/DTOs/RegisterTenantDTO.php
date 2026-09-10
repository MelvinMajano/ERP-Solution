<?php

namespace Modules\Core\DTOs;

/**
 * Objeto de Transferencia de Datos (DTO) inmutable para la creación de un nuevo Tenant
 * y su usuario Administrador inicial.
 */
final readonly class RegisterTenantDTO
{
    /**
     * Constructor promocionado con propiedades de solo lectura.
     *
     * @param string $companyName Nombre comercial o razón social de la empresa.
     * @param string $subdomain Identificador único para el acceso Multi-Tenant.
     * @param string $firstNames Nombres del usuario administrador principal.
     * @param string $lastNames Apellidos del usuario administrador principal.
     * @param string $username Nombre de usuario único para inicio de sesión.
     * @param string $email Correo electrónico institucional o personal del administrador.
     * @param string $password Contraseña en texto plano para procesar en el servicio.
     */
    public function __construct(
        public string $companyName,
        public string $subdomain,
        public string $firstNames,
        public string $lastNames,
        public string $username,
        public string $email,
        public string $password
    ) {}

    /**
     * Instancia un DTO a partir de los datos sanitizados por el validador de Rakit.
     *
     * @param array<string, mixed> $data Arreglo con la información validada.
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            companyName: trim((string) ($data['company_name'] ?? '')),
            subdomain: strtolower(trim((string) ($data['subdomain'] ?? ''))),
            firstNames: trim((string) ($data['first_names'] ?? '')),
            lastNames: trim((string) ($data['last_names'] ?? '')),
            username: trim((string) ($data['username'] ?? '')),
            email: strtolower(trim((string) ($data['email'] ?? ''))),
            password: (string) ($data['password'] ?? '')
        );
    }

    /**
     * Mapea las propiedades correspondientes a la entidad Tenant.
     *
     * @return array<string, mixed>
     */
    public function toTenantArray(): array
    {
        return [
            'company_name' => $this->companyName,
            'subdomain'    => $this->subdomain,
        ];
    }

    /**
     * Mapea las propiedades correspondientes a la entidad User.
     *
     * @param int $tenantId ID del Tenant registrado.
     * @param int $roleId ID del Rol Administrador creado.
     * @param string $hashedPassword Contraseña procesada con el algoritmo Hash.
     * @return array<string, mixed>
     */
    public function toUserArray(int $tenantId, int $roleId, string $hashedPassword): array
    {
        return [
            'tenant_id'   => $tenantId,
            'rol_id'      => $roleId,
            'first_names' => $this->firstNames,
            'last_names'  => $this->lastNames,
            'username'    => $this->username,
            'email'       => $this->email,
            'password'    => $hashedPassword,
            'created_by'  => null,
        ];
    }
}
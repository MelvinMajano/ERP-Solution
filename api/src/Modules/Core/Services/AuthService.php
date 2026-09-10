<?php

namespace Modules\Core\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Infrastructure\Base\BaseService;
use Infrastructure\Exceptions\UnauthorizedException;
use Modules\Core\DTOs\LoginDTO;
use Domain\Entities\User;
use Throwable;

class AuthService extends BaseService
{
    /**
     * Decodifica y valida la firma del token JWT.
     */
    public function checkToken(string $token): ?object
    {
        try {
            $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
            return JWT::decode($token, new Key($secret, 'HS256'));
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Paso 1: Verifica la existencia del correo globalmente y retorna las empresas asociadas.
     *
     * @param LoginDTO $dto
     * @return array<string, mixed>
     */
    public function checkEmail(LoginDTO $dto): array
    {
        // Buscar usuarios asociados al email (pueden pertenecer a múltiples tenants)
        $users = User::withoutGlobalScopes()
            ->with('tenant')
            ->where('email', $dto->email)
            ->where('is_active', true)
            ->get();

        if ($users->isEmpty()) {
            throw new UnauthorizedException('Las credenciales proporcionadas no son válidas.', [
                'email' => 'user_not_found'
            ]);
        }

        $firstUser = $users->first();
        $tenants = [];

        foreach ($users as $u) {
            if ($u->tenant && $u->tenant->is_active) {
                $tenants[] = [
                    'tenant_id'    => $u->tenant->id,
                    'company_name' => $u->tenant->company_name,
                    'subdomain'    => $u->tenant->subdomain,
                ];
            }
        }

        if (empty($tenants)) {
            throw new UnauthorizedException('El usuario no tiene empresas activas asociadas.', [
                'tenant' => 'no_active_tenants'
            ]);
        }

        // Emitir Pre-Auth Token (Válido por 10 minutos)
        $preAuthToken = $this->generatePreAuthToken($firstUser->id, $dto->email, $tenants);

        return [
            'pre_auth_token' => $preAuthToken,
            'tenants'        => $tenants
        ];
    }

    /**
     * Paso 2: Valida la contraseña para el tenant seleccionado y emite el Session Token.
     *
     * @param int $userId ID obtenido del PreAuthMiddleware.
     * @param LoginDTO $dto
     * @return array<string, mixed>
     */
    public function loginPassword(int $userId, LoginDTO $dto): array
    {
        // Buscar al usuario dentro del tenant específico
        $user = User::withoutGlobalScopes()
            ->where('id', $userId)
            ->where('tenant_id', $dto->tenantId)
            ->where('is_active', true)
            ->first();

        if (!$user) {
            throw new UnauthorizedException('No tiene acceso a la empresa seleccionada.', [
                'tenant' => 'invalid_tenant'
            ]);
        }

        // Verificar hash de contraseña
        if (!password_verify($dto->password, $user->password)) {
            throw new UnauthorizedException('Contraseña incorrecta.', [
                'password' => 'invalid_password'
            ]);
        }

        // Emitir Session Token
        $sessionToken = $this->generateSessionToken($user);

        return [
            'access_token' => $sessionToken,
            'token_type'   => 'Bearer',
            'expires_in'   => isset($_ENV['JWT_EXPIRES_IN']) ? (int) $_ENV['JWT_EXPIRES_IN'] : 28800,
            'user' => [
                'id'        => $user->id,
                'username'  => $user->username,
                'email'     => $user->email,
                'tenant_id' => $user->tenant_id,
            ]
        ];
    }

    /**
     * Genera el JWT temporal para la fase de pre-autenticación.
     */
    private function generatePreAuthToken(int $userId, string $email, array $tenants): string
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
        $issuedAt = time();
        
        $payload = [
            'iat'     => $issuedAt,
            'exp'     => $issuedAt + 600, // 10 minutos
            'sub'     => $userId,
            'email'   => $email,
            'type'    => 'pre_auth_token',
            'tenants' => $tenants,
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    /**
     * Genera el JWT final para la sesión del usuario.
     */
    private function generateSessionToken(User $user): string
    {
        $secret = $_ENV['JWT_SECRET'] ?? 'default_secret';
        $issuedAt = time();
        $ttl = isset($_ENV['JWT_EXPIRES_IN']) ? (int) $_ENV['JWT_EXPIRES_IN'] : 28800;

        $payload = [
            'iat'       => $issuedAt,
            'exp'       => $issuedAt + $ttl,
            'sub'       => $user->id,
            'tenant_id' => $user->tenant_id,
            'role'      => $user->role ?? 'admin',
            'type'      => 'session_token',
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }
}
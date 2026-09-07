<?php

declare(strict_types=1);

namespace Infrastructure\Context;

/**
 * Clase TenantContext.
 *
 * Gestiona el contexto del inquilino (tenant) de forma aislada y nativa.
 * La seguridad contra fugas de datos en entornos persistentes (Swoole, RoadRunner)
 * se garantiza mediante la limpieza obligatoria con clear() en el middleware.
 */
class TenantContext
{
    /**
     * @var int|null Identificador del tenant para el ciclo de vida de la petición actual.
     */
    private static ?int $tenantId = null;

    /**
     * Establece el ID del tenant en el contexto actual.
     *
     * @param int|null $tenantId Identificador único del inquilino.
     * @return void
     */
    public static function set(?int $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    /**
     * Obtiene el ID del tenant activo.
     *
     * @return int|null El ID del tenant o null si no se ha definido.
     */
    public static function get(): ?int
    {
        return self::$tenantId;
    }

    /**
     * Limpia el estado del tenant en memoria.
     * Debe ejecutarse en el bloque `finally` del middleware.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$tenantId = null;
    }
}
<?php

declare(strict_types=1);

namespace Modules\Inventory\Validators;

use Infrastructure\Base\BaseValidator;

/**
 * Validador HTTP para las peticiones relacionadas a Motivos de Movimiento.
 *
 * Ejecuta validaciones sintácticas y de tipos sobre el Payload recibido en la petición
 * haciendo uso de Rakit Validator antes de pasar los datos al Servicio de Aplicación.
 */
class MovementReasonValidator extends BaseValidator
{
    /**
     * Alias amigables para los nombres de los atributos en los mensajes de error.
     *
     * @var array<string, string>
     */
    public const ALIAS = [
        'id'            => 'identificador del motivo',
        'name'          => 'nombre del motivo',
        'movement_type' => 'tipo de movimiento',
        'is_active'     => 'estado activo',
    ];

    /**
     * Valida la estructura y reglas del payload para la creación de un motivo.
     *
     * @param array<string, mixed>|null $data Payload de la petición HTTP.
     * @return array<string, mixed> Datos filtrados y validados.
     * @throws \Infrastructure\Exceptions\ValidationException Si alguna regla sintáctica falla.
     */
    public static function createValidation(?array $data): array
    {
        $rules = [
            'name'          => 'required|max:150',
            'movement_type' => 'required|in:IN,OUT,in,out',
            'is_active'     => 'nullable|boolean',
        ];

        $validation = self::makeValidator($data ?? [], $rules);
        if (defined('static::ALIAS')) {
            $validation->setAliases(static::ALIAS);
        }
        $validation->validate();

        return static::validationCheck($validation);
    }

    /**
     * Valida el payload recibido para actualizar un motivo existente.
     *
     * @param int $id Identificador extraído de la URL/Ruta.
     * @param array<string, mixed>|null $data Payload recibido en el body de la petición.
     * @return array<string, mixed> Datos procesados y validados.
     * @throws \Infrastructure\Exceptions\ValidationException Si las validaciones fallan.
     */
    public static function updateValidation(int $id, ?array $data): array
    {
        $payload = array_merge($data ?? [], ['id' => $id]);

        $rules = [
            'id'            => 'required|integer|min:1',
            'name'          => 'nullable|max:150',
            'movement_type' => 'nullable|in:IN,OUT,in,out',
            'is_active'     => 'nullable|boolean',
        ];

        $validation = self::makeValidator($payload, $rules);
        if (defined('static::ALIAS')) {
            $validation->setAliases(static::ALIAS);
        }
        $validation->validate();

        return static::validationCheck($validation);
    }
}
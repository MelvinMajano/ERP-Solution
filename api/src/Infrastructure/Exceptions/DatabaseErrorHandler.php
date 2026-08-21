<?php

namespace Infrastructure\Exceptions;

use Illuminate\Database\QueryException;

class DatabaseErrorHandler
{
    /**
     * Mapea códigos de error SQL de MySQL/MariaDB a mensajes amigables y códigos HTTP.
     */
    public static function handle(QueryException $e): array
    {
        $code = $e->errorInfo[1] ?? 0;
        $sqlMessage = $e->errorInfo[2] ?? 'Error SQL no especificado';

        return match ($code) {
            1048 => ['message' => 'Falta un campo obligatorio.', 'code' => 422],
            1062 => ['message' => 'El valor ingresado ya está registrado.', 'code' => 409],
            1406 => ['message' => 'Uno de los campos excede la longitud permitida.', 'code' => 422],
            1364 => ['message' => 'Campo obligatorio no tiene valor asignado.', 'code' => 422],
            1451 => ['message' => 'No se puede eliminar: el registro está relacionado con otros.', 'code' => 409],
            1452 => ['message' => 'El valor de relación no existe.', 'code' => 400],
            1054 => ['message' => 'Se ha enviado un campo inválido.', 'code' => 400],
            1146 => ['message' => 'Tabla de base de datos no encontrada.', 'code' => 500],
            1216 => ['message' => 'No se puede asignar una relación inexistente.', 'code' => 400],
            1217 => ['message' => 'No se puede eliminar el registro por restricciones.', 'code' => 400],
            1265 => ['message' => 'El valor ingresado no concuerda con los valores permitidos.', 'code' => 400],
            default => ['message' => 'Error de base de datos: ' . $sqlMessage, 'code' => 500],
        };
    }
}
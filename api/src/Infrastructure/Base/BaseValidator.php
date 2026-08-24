<?php

namespace Infrastructure\Base;

use Infrastructure\Exceptions\ValidationException;
use Infrastructure\Validators\Rules\Address;
use Infrastructure\Validators\Rules\AlphaExtended;
use Infrastructure\Validators\Rules\Description;
use Infrastructure\Validators\Rules\ExistsActive;
use Infrastructure\Validators\Rules\ExistsIn;
use Infrastructure\Validators\Rules\InEnum;
use Infrastructure\Validators\Rules\OnlyDigits;
use Infrastructure\Validators\Rules\PhoneNumber;
use Infrastructure\Validators\Rules\SecurePassword;
use Infrastructure\Validators\Rules\UniqueIn;
use Infrastructure\Validators\Rules\Username;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

abstract class BaseValidator
{
    /** Mensajes de error personalizados */
    protected const MESSAGES = [
        'required'      => ':attribute es requerido.',
        'alpha'         => ':attribute no es válido.',
        'alpha_spaces'  => ':attribute no es válido.',
        'regex'         => ':attribute \':value\' no es un formato válido.',
        'email'         => ':attribute debe ser un correo válido.',
        'numeric'       => ':attribute debe ser un valor numérico.',
        'alpha_num'     => ':attribute debe ser un valor alfanumérico.',
        'in'            => ':attribute únicamente acepta los valores :allowed_values',
        'min'           => ':attribute no cumple con el valor mínimo requerido.',
        'max'           => ':attribute excede el valor máximo requerido.',
        'between'       => ':attribute se encuentra fuera del rango permitido.',
        'integer'       => ':attribute debe ser un valor entero.',
        'boolean'       => ':value no es un valor aceptado para el campo :attribute.',
        'date'          => ':attribute no es un formato de fecha válido.',
        'array'         => ':attribute debe ser un conjunto de elementos.',
        'after'         => ':attribute debe ser una fecha posterior a :time',
        'same'          => ':attribute debe ser igual a :field',
        'uploaded_file' => ':attribute no es un archivo válido, tamaño debe ser entre :min_size y :max_size, formatos permitidos: :allowed_types'
    ];

    /** Instancia e inicializa Rakit Validator */
    protected static function makeValidator(?array $data, array $rules): Validation
    {
        $validator = new Validator();
        static::register($validator);
        $validator->setMessages(self::MESSAGES);
        $validator->setTranslations([
            'and' => 'y',
            'or'  => 'o'
        ]);

        return $validator->make($data ?? [], $rules);
    }

    /**
     * Comprueba la validación. Si falla, dispara la excepción de infraestructura.
     * Si pasa, retorna directamente el array de datos limpios.
     * 
     * @throws ValidationException
     */
    protected static function validationCheck(Validation $validation): array
    {
        if ($validation->fails()) {
            throw new ValidationException(
                "Los datos ingresados no superan las validaciones de formato.",
                $validation->errors()->firstOfAll()
            );
        }

        return $validation->getValidatedData();
    }

    /** Reglas de paginación reutilizables */
    protected static function paginationRules(bool $nullable = false): array
    {
        $p = $nullable ? 'nullable|' : '';
        return [
            'page'     => $p . 'numeric|min:1',
            'pageSize' => $p . 'numeric|min:1|max:100',
        ];
    }

    protected static function sortDirRule(bool $nullable = false): array
    {
        $p = $nullable ? 'nullable|' : '';
        return ['sortDir' => $p . 'in:asc,desc'];
    }

    protected static function sortByRule(array $allowedFields, bool $nullable = false): array
    {
        $p = $nullable ? 'nullable|' : '';
        return ['sortBy' => $p . 'in:' . implode(',', $allowedFields)];
    }

    protected static function paginationAliases(): array
    {
        return [
            'page'     => 'página',
            'pageSize' => 'tamaño de página',
            'sortBy'   => 'ordenar por',
            'sortDir'  => 'dirección de orden',
        ];
    }

    /** Registro centralizado de reglas personalizadas */
    private static function register(Validator $validator): void
    {
        $validator->addValidator(AlphaExtended::RULE, new AlphaExtended());
        $validator->addValidator(SecurePassword::RULE, new SecurePassword());
        $validator->addValidator(Username::RULE, new Username());
        $validator->addValidator(InEnum::RULE, new InEnum());
        $validator->addValidator(ExistsActive::RULE, new ExistsActive());
        $validator->addValidator(ExistsIn::RULE, new ExistsIn());
        $validator->addValidator(Address::RULE, new Address());
        $validator->addValidator(Description::RULE, new Description());
        $validator->addValidator(UniqueIn::RULE, new UniqueIn());
        $validator->addValidator(PhoneNumber::RULE, new PhoneNumber());
        $validator->addValidator(OnlyDigits::RULE, new OnlyDigits());
    }
}
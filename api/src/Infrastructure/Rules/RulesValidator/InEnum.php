<?php

namespace Infrastructure\Rules\RulesValidator;

use Rakit\Validation\Rule;
use BackedEnum;

/**
 * Validacion valor debe ser un elemento valido del enumarable `enum`
 * 
 * @example `$rules = ['status'=> 'in_enum:'.MyEnum::class]`
 */
class InEnum extends Rule
{
    public const RULE = 'in_enum';
    protected $message = ':value no es un valor aceptado para el campo :attribute.';
    protected $fillableParams = ['enum'];

    public function check(
        mixed $value
        ): bool
    {
        /** @var BackedEnum */
        $enum = $this->parameter('enum');

        $result = $enum::tryFrom($value);
        
        return !is_null($result);
    }
}

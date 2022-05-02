<?php

namespace AppFinance\Shared\Validators;

use AppFinance\Shared\Exceptions\ValidationException;

class NumberValidator
{
    public static function validateOrException(
        $field_description,
        $value
    )
    {
        if (! is_numeric($value)) {
            throw new ValidationException("$field_description não é um dado númerico.");
        }
        return true;
    }
}
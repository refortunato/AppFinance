<?php

namespace AppFinance\Shared\Validators;

use AppFinance\Shared\Exceptions\ValidationException;

class TextValidator
{
    public static function validateOrException(
        $field_description,
        $text_value,
        $rules = ['max' => 2000, 'min' => 0, 'blank' => false]
    )
    {
        if (isset($rules['max'])) {
            if (strlen($text_value) > $rules['max']) {
                throw new ValidationException("$field_description contém mais de ".$rules['max']." caracteres");
            }
        }
        if (isset($rules['blank'])) {
            if (! $rules['blank'] && ($text_value === "" || $text_value === null)) {
                throw new ValidationException("$field_description não pode estar vazio.");
            }
        }
        if (isset($rules['min'])) {
            if (strlen($text_value) < $rules['min']) {
                throw new ValidationException("$field_description contém menos de ".$rules['min']." caracteres");
            }
        }
        return true;
    }
}
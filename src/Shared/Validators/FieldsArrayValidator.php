<?php

namespace AppFinance\Shared\Validators;

use AppFinance\Shared\Exceptions\ValidationException;

class FieldsArrayValidator
{
    private array $array_fields;

    private function __construct(array $fields)
    {
        $this->array_fields = $fields;
    }

    public static function create($array): static
    {
        return new static($array);
    }

    public function checkField(
        string $field_name, 
        string $field_description = '', 
        string $validate_as = 'text', 
        array $rule = ['max' => 2000, 'min' => 0, 'blank' => false]
    ): static
    {
        if (empty($field_description)) {
            $field_description = $field_name;
        }
        if (! isset($this->array_fields[$field_name])) {
            throw new ValidationException("Campo ".$field_description." deve ser informado");
        }
        if (strtolower($validate_as) === 'text') {
            TextValidator::validateOrException($field_description, $this->array_fields[$field_name], $rule);
        }
        else if (strtolower($validate_as) === 'numeric') {
            NumberValidator::validateOrException($field_description, $this->array_fields[$field_name]);
        }

        return $this;
    }
}
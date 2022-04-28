<?php

namespace AppFinance\Domain\ValueObjects;

use AppFinance\Shared\Helpers\Strings;

class Cpf
{
    private string $cpf_value;

    public function __construct(string $rawValue)
    {
        $this->cpf_value = Strings::onlyNumber($rawValue);

        if (! $this->validate()) {
            throw new \DomainException("CPF inválido");
        }
    }

    private function validate(): bool
    {
        if (strlen($this->cpf_value) != 11 || preg_match('/([0-9])\1{10}/', $this->cpf_value)) {
            return false;
        }
        $number_quantity_to_loop = [9, 10];
        foreach ($number_quantity_to_loop as $item) {
            $sum = 0;
            $number_to_multiplicate = $item + 1;
            for ($index = 0; $index < $item; $index++) {
                $sum += $this->cpf_value[$index] * ($number_to_multiplicate--);
            }
            $result = (($sum * 10) % 11);
            if ($this->cpf_value[$item] != $result) {
                return false;
            }
        }
    
        return true;
    }
}
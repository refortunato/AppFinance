<?php

namespace AppFinance\Domain\ValueObjects;

use AppFinance\Shared\Helpers\Strings;

class Cpf implements IDocument
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
        $cpf = $this->cpf_value;
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public function getValue(): string
    {
        return $this->cpf_value;
    }

    public function getType(): string
    {
        return 'CPF';
    }
}
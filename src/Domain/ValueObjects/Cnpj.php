<?php

namespace AppFinance\Domain\ValueObjects;

use AppFinance\Shared\Helpers\Strings;

class Cnpj
{
    private string $cnpj_value;

    public function __construct(string $rawValue)
    {
        $this->cnpj_value = Strings::onlyNumber($rawValue);

        if (! $this->validate()) {
            throw new \DomainException("CNPJ inválido");
        }
    }

    private function validate(): bool 
    {
        // Valida tamanho
	    if (strlen($this->cnpj_value) != 14) {
            return false;
        }
        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $this->cnpj_value)) {
            return false;	
        }
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $this->cnpj_value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($this->cnpj_value[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $this->cnpj_value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        return $this->cnpj_value[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public function getValue(): string
    {
        return $this->cnpj_value;
    }
}
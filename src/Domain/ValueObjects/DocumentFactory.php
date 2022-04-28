<?php

namespace AppFinance\Domain\ValueObjects;

use AppFinance\Shared\Helpers\Strings;

class DocumentFactory
{
    public static function create(string $document_value): Cpf|Cnpj
    {
        $document_value = Strings::onlyNumber($document_value);
        if (strlen($document_value) < 12) {
            return new Cpf($document_value);
        }
        return new Cnpj($document_value);
    }
}
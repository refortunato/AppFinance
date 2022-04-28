<?php

namespace AppFinance\Domain\ValueObjects;

class Email 
{
    private string $email_address;

    public function __construct(string $email_address)
    {
        $this->email_address = strtolower($email_address);

        $this->validate();
    }

    public function getEmailAddress(): string
    {
        return $this->email_address;
    }

    private function validate() : void
    {
        if (!filter_var($this->email_address, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException("E-mail inválido");
        }
    }

    public function __toString()
    {
        return $this->email_address;
    }
}
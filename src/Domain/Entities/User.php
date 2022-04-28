<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\ValueObjects\Email;
use AppFinance\Domain\ValueObjects\IDocument;
use AppFinance\Shared\Entity;

abstract class User extends Entity
{
    private Email $email;
    private string $name;
    private string $user_type;
    private string $hashed_password;
    private IDocument $document;

    private function __construct(
        $id,
        Email $email,
        IDocument $document,
        string $name,
        string $hashed_password
    )
    {
        parent::__construct($id);
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserType(): string
    {
        return $this->user_type;
    }

    public function getDocument(): IDocument
    {
        return $this->document;
    }

    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    protected function validate()
    {
        if (! in_array($this->user_level, UserType::getValues())  ) {
            throw new \DomainException("Tipo de usuário é inválido.");
        }
        if (empty($this->hashed_password)) {
            throw new \DomainException("Senha do usuário não pode estar vazia.");
        }
    }
}
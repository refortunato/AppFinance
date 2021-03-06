<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\ValueObjects\Email;
use AppFinance\Domain\ValueObjects\IDocument;
use AppFinance\Shared\Entity;

abstract class User extends Entity
{
    protected Email $email;
    protected string $name;
    protected string $user_type;
    protected string $hashed_password;
    protected IDocument $document;
    protected float $total_account_money;

    public function __construct(
        $id,
        Email $email,
        IDocument $document,
        string $name,
        string $hashed_password
    )
    {
        parent::__construct($id);
        $this->name = trim($name);
        $this->document = $document;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->total_account_money = 1000;
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

    public function getTotalAccountMoney(): float
    {
        return $this->total_account_money;
    }

    public function updateTotalAccountMoney(float $value)
    {
        $this->total_account_money = round($value, 2);
    }

    protected function validate()
    {
        if (strlen($this->name) < 3) {
            throw new \DomainException("Nome deve ter no mínimo 3 caracteres.");
        }
        if (! in_array($this->user_type, UserType::getValues())  ) {
            throw new \DomainException("Tipo de usuário é inválido.");
        }
        if (empty($this->hashed_password)) {
            throw new \DomainException("Senha do usuário não pode estar vazia.");
        }
    }
}
<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\ValueObjects\Email;
use AppFinance\Domain\ValueObjects\IDocument;

class CommonUser extends User
{
    public function __construct(
        $id,
        Email $email,
        IDocument $document,
        string $name,
        string $hashed_password
    )
    {
        parent::__construct(
            $id,
            $email,
            $document,
            $name,
            $hashed_password
        );
        $this->user_type = UserType::COMMON;
        $this->validate();
    }
}
<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\ValueObjects\Email;

class CommonUser extends User
{
    public function __construct(
        $id,
        Email $email,
        string $name,
        string $hashed_password
    )
    {
        parent::__construct(
            $id,
            $email,
            $name,
            $hashed_password
        );
        $this->user_type = UserType::COMMON;
        $this->validate();
    }
}
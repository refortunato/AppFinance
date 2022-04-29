<?php

namespace AppFinance\Infra;

use AppFinance\Protocols\PasswordHasher;

class PasswordHasherAdapter implements PasswordHasher
{
    private $currentHashAlgorithm = PASSWORD_DEFAULT;
    private $options = [
        'cost' => 12,
    ];

    public function hash(string $password): string
    {
        return password_hash($password, $this->currentHashAlgorithm , $this->options);
    }

    public function verify(string $password, string $hashed_password): bool
    {
        return password_verify($password, $hashed_password);
    }

    public function rehashIfNecessary(string $password, string $hashed_password): string
    {
        $password_needs_rehash = password_needs_rehash($hashed_password, $this->currentHashAlgorithm, $this->options);
        if ($password_needs_rehash) {
            return $this->hash($password);
        }
        return $hashed_password;
    }

}
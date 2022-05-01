<?php

namespace AppFinance\Application\UseCases\TokenIsAuthorized;

use AppFinance\Protocols\Jwt;

class TokenIsAuthorized
{
    private Jwt $jwt;

    public function __construct(Jwt $jwt)
    {
        $this->jwt = $jwt;
    }

    public function execute(string $token): bool
    {
        $this->jwt->decrypt($token);
        return true;
    }
}
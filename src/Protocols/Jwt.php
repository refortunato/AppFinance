<?php

namespace AppFinance\Protocols;

interface Jwt
{
    public function encrypt(array $payload_data): string;
    public function decrypt(string $jwt);
}
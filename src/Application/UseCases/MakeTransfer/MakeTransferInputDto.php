<?php

namespace AppFinance\Application\UseCases\MakeTransfer;

class MakeTransferInputDto
{
    private string $user_origin_id;
    private string $user_destiny_id;
    private float $value;

    public function __construct(
        string $user_origin_id,
        string $user_destiny_id,
        float $value
    )
    {
        $this->user_origin_id = $user_origin_id;
        $this->user_destiny_id = $user_destiny_id;
        $this->value = $value;
    }

    public function getUserOriginId(): string
    {
        return $this->user_origin_id;
    }

    public function getUserDestinyId(): string
    {
        return $this->user_destiny_id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

}
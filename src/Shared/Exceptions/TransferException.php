<?php

namespace AppFinance\Shared\Exceptions;

class TransferException extends \Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
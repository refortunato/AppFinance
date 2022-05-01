<?php

namespace AppFinance\Application\UseCases\MakeTransfer;

interface ITransferAuthorizer
{
    public function verify(): bool;
}
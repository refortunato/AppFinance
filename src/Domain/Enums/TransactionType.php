<?php

namespace AppFinance\Domain\Enums;

use AppFinance\Shared\Enum;

class TransactionType extends Enum
{
    const TRANSFER = 'TRANSFER';
    const WITHDRAW = 'WITHDRAW';
}
<?php

namespace AppFinance\Domain\Repositories;

use AppFinance\Domain\Entities\Transaction;
use AppFinance\Domain\Entities\Transfer;

interface ITransactionRepository
{
    public function save(Transaction $transaction): Transaction;
    public function getAllOfAccount(string $account_id): array;
}
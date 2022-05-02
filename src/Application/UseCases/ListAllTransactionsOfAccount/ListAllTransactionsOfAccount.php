<?php

namespace AppFinance\Application\UseCases\ListAllTransactionsOfAccount;

use AppFinance\Domain\Repositories\ITransactionRepository;

class ListAllTransactionsOfAccount
{
    private ITransactionRepository $transaction_repository;

    public function __construct(
        ITransactionRepository $transaction_repository,
    )
    {
        $this->transaction_repository = $transaction_repository;
    }

    public function execute(?string $account_id): array
    {
        $transactions = $this->transaction_repository->getAllOfAccount($account_id);
        return $transactions;
    }
}
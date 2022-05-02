<?php

namespace AppFinance\Infra\Repositories\Sql;

use AppFinance\Domain\Entities\Transaction;
use AppFinance\Domain\Mappers\TransactionMap;
use AppFinance\Domain\Repositories\ITransactionRepository;
use AppFinance\Infra\DB\DataMapper\Repositories\Repository;
use AppFinance\Shared\Entity;

class TransactionRepositorySql extends Repository implements ITransactionRepository
{
    protected ?string $table = 'transactions';

    protected function makeEntity(array $fields): ?Entity
    {
        return TransactionMap::toEntity($fields);
    }

    protected function mapEntityToArrayFields(Entity $user): array
    {
        return TransactionMap::toPersistance($user);
    }

    public function save(Transaction $transfer): Transaction
    {
        $exists = $this->first($transfer->getId()) ? true : false;
        if ($exists) {
            $this->update($transfer);
            return $transfer;
        }
        $this->insert($transfer);
        return $transfer;
    }

    public function getAllOfAccount(string $account_id): array
    {
        $query = 'select * from '.$this->table.' where origin_account_id = :account_id or destiny_account_id = :account_id order by run_date';
        $params = [
            ':account_id' => $account_id,
        ];
        $transactions = [];
        $transactions_list = $this->driver->executeSelectFromText($query, $params);
        foreach($transactions_list as $transaction_data) {
            $transactions[] = TransactionMap::toEntity($transaction_data);
        }
        return $transactions;
    }
}
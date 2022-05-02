<?php

namespace AppFinance\Domain\Mappers;

use AppFinance\Domain\Entities\Transaction;
use AppFinance\Shared\Helpers\DateTime;

class TransactionMap
{
    public static function toArray(Transaction $transaction): array
    {
        $array = [];
        $array['id'] = $transaction->getId();
        $array['run_date'] = $transaction->getRunDate()->format('Y-m-d\TH:i:s');
        $array['origin_account_id'] = $transaction->getOriginAccountId();
        $array['destiny_account_id'] = $transaction->getDestinyAccountId();
        $array['transaction_type'] = $transaction->getTransactionType();
        $array['origin_transaction_id'] = $transaction->getOriginTransactionId();
        $array['value'] = $transaction->getValue();        
        return $array;
    }

    public static function toPersistance(Transaction $transaction): array
    {
        $array = [];
        $array['id'] = $transaction->getId();
        $array['run_date'] = $transaction->getRunDate()->format('Y-m-d H:i:s');
        $array['origin_account_id'] = $transaction->getOriginAccountId();
        $array['destiny_account_id'] = $transaction->getDestinyAccountId();
        $array['transaction_type'] = $transaction->getTransactionType();
        $array['origin_transaction_id'] = $transaction->getOriginTransactionId();
        $array['value'] = $transaction->getValue();  
        return $array;
    }

    public static function toEntity(array $fields): ?Transaction
    {
        $transaction = new Transaction(
            $fields['id'],
            $fields['origin_account_id'],
            $fields['destiny_account_id'],
            $fields['transaction_type'],
            (float) $fields['value'],
            DateTime::createDateTimeObjFromDateString($fields['run_date'])
        );
        if (! empty($fields['origin_transaction_id'])) {
            $transaction->setOriginTransactionId($fields['origin_transaction_id']);
        }
        return $transaction;
    }
}
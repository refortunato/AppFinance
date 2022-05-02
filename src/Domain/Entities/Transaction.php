<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Domain\Enums\TransactionType;
use AppFinance\Shared\Entity;

class Transaction extends Entity
{
    private \DateTime $run_date;
    private ?string $origin_account_id;
    private ?string $destiny_account_id;
    private string $transaction_type;
    private float $value;
    private ?string $origin_transaction_id;

    public function __construct(
        $id,
        ?string $origin_account_id,
        ?string $destiny_account_id,
        string $transaction_type,
        float $value,
        ?\DateTime $run_date = null
    )
    {
        parent::__construct($id);  
        $this->run_date = (! empty($run_date)) ? $run_date : new \DateTime('now', new \DateTimeZone('UTC'));
        $this->origin_account_id = $origin_account_id;
        $this->destiny_account_id = $destiny_account_id;
        $this->transaction_type = $transaction_type;
        $this->value = round($value, 2);
        $this->origin_transaction_id = null;

        $this->validate();
    }

    public function getRunDate(): \DateTime
    {
        return $this->run_date;
    }

    public function getOriginAccountId (): string 
    { 
        return $this->origin_account_id;
    }

    public function getDestinyAccountId(): string
    { 
        return $this->destiny_account_id;
    }

    public function getTransactionType(): string
    {
        return $this->transaction_type;
    }

    public function getValue(): float
    { 
        return $this->value;
    }

    public function getOriginTransactionId(): ?string
    {
        return $this->origin_transaction_id;
    }

    public function setOriginTransactionId(string $transaction_id)
    {
        $this->origin_transaction_id = $transaction_id;
    }

    private function validate(): void
    {
        if (! in_array($this->transaction_type, TransactionType::getValues())  ) {
            throw new \DomainException("Tipo de transação é inválido.");
        }

        if (empty($this->origin_account_id) && empty($this->destiny_account_id)) {
            throw new \DomainException("Para efeturar uma transação é necessário informar pelo menos uma conta de origem ou uma conta de destino.");
        }
    }
}
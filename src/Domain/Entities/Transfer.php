<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Shared\Entity;

class Transfer extends Entity
{
    private \DateTime $run_date;
    private CommonUser $origin_account;
    private User $destiny_account;
    private float $value;

    public function __construct(
        $id,
        CommonUser $origin_account,
        User $destiny_account,
        float $value
    )
    {
        parent::__construct($id);  
        $this->run_date = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->origin_account = $origin_account;
        $this->destiny_account = $destiny_account;
        $this->value = round($value, 2);
        $this->validate();
    }

    public function getRunDate(): \DateTime
    {
        return $this->run_date;
    }

    public function getOriginAccount (): User 
    { 
        return $this->origin_account;
    }

    public function getDestinyAccount(): User
    { 
        return $this->destiny_account;
    }
    public function getValue(): float
    { 
        return $this->value;
    }

    private function validate(): void
    {
        if ($this->value <= 0) {
            throw new \DomainException("Valor de transferência deve ser maior que 0");
        }

        if ($this->origin_account->getTotalAccountMoney() < $this->value) {
            throw new \DomainException('A conta de origem não possui o valor necessário para realizar a transferência.');
        }
    }
}
<?php

namespace AppFinance\Domain\Services;

use AppFinance\Domain\Entities\CommonUser;
use AppFinance\Domain\Entities\Transaction;
use AppFinance\Domain\Entities\User;
use AppFinance\Domain\Enums\TransactionType;

class Transfer
{
    private CommonUser $origin_account;
    private User $destiny_account;
    private float $value;

    private function __construct(
        CommonUser $origin_account,
        User $destiny_account,
        float $value
    )
    {
        $this->origin_account = $origin_account;
        $this->destiny_account = $destiny_account;
        $this->value = round($value, 2);
        $this->validate();

        $this->origin_account->updateTotalAccountMoney(
            $this->origin_account->getTotalAccountMoney() - $this->value
        );
        $this->destiny_account->updateTotalAccountMoney(
            $this->destiny_account->getTotalAccountMoney() + $this->value
        );
    }

    public static function create(
        CommonUser $origin_account,
        User $destiny_account,
        float $value
    ): static
    {
        return new static ($origin_account, $destiny_account, $value);
    }

    public function getTransaction(): Transaction
    {
        return new Transaction(
            '',
            $this->origin_account->getId(),
            $this->destiny_account->getId(),
            TransactionType::TRANSFER,
            $this->value
        );
    }

    public function getOriginAccount(): CommonUser
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

    private function validate()
    {
        if ($this->value <= 0) {
            throw new \DomainException("Valor de transferência deve ser maior que 0");
        }

        if ($this->origin_account->getTotalAccountMoney() < $this->value) {
            throw new \DomainException('A conta de origem não possui o valor necessário para realizar a transferência.');
        }
    }
}
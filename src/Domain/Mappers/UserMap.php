<?php

namespace AppFinance\Domain\Mappers;

use AppFinance\Domain\Entities\CommonUser;
use AppFinance\Domain\Entities\StoreUser;
use AppFinance\Domain\Entities\User;
use AppFinance\Domain\Enums\UserType;
use AppFinance\Domain\ValueObjects\DocumentFactory;
use AppFinance\Domain\ValueObjects\Email;

class UserMap
{
    public static function toArray(User $user): array
    {
        $array = [];
        $array['id'] = $user->getId();
        $array['name'] = $user->getName();
        $array['email'] = (string) $user->getEmail();
        $array['user_type'] = $user->getUserType();
        $array['cpf'] = $user->getDocument()->getType() === 'CPF' ? $user->getDocument()->getValue() : '';
        $array['cnpj'] = $user->getDocument()->getType() === 'CNPJ' ? $user->getDocument()->getValue() : '';
        $array['total_account_money'] = $user->getTotalAccountMoney();
        
        return $array;
    }

    public static function toPersistance(User $user): array
    {
        $array = [];
        $array['id'] = $user->getId();
        $array['name'] = $user->getName();
        $array['email'] = (string) $user->getEmail();
        $array['user_type'] = $user->getUserType();
        $array['password'] = $user->getHashedPassword();
        $array['cpf'] = $user->getDocument()->getType() === 'CPF' ? $user->getDocument()->getValue() : '';
        $array['cnpj'] = $user->getDocument()->getType() === 'CNPJ' ? $user->getDocument()->getValue() : '';
        $array['total_account_money'] = $user->getTotalAccountMoney();

        return $array;
    }

    public static function toEntity(array $fields): ?User
    {
        $document_number = (! empty($fields['cpf'])) ? $fields['cpf'] : $fields['cnpj'];
        if ($fields['user_type'] === UserType::STORE) {
            $user = new StoreUser(
                $fields['id'],
                new Email($fields['email']),
                DocumentFactory::create($document_number),
                $fields['name'],
                $fields['password']
            );
        }
        else {
            $user = new CommonUser(
                $fields['id'],
                new Email($fields['email']),
                DocumentFactory::create($document_number),
                $fields['name'],
                $fields['password']
            );
        }
        $user->updateTotalAccountMoney($fields['total_account_money']);

        return $user;
    }


}
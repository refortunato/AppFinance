<?php

namespace AppFinance\Infra\Repositories\Sql;

use AppFinance\Domain\Entities\User;
use AppFinance\Domain\Mappers\UserMap;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Infra\DB\DataMapper\Repositories\Repository;
use AppFinance\Shared\Entity;

class UserRepositorySql extends Repository implements IUserRepository
{
    protected ?string $table = 'users';

    protected function makeEntity(array $fields): ?Entity
    {
        return UserMap::toEntity($fields);
    }

    protected function mapEntityToArrayFields(Entity $user): array
    {
        return UserMap::toPersistance($user);
    }

    public function save(User $user): User
    {
        $exists = $this->first($user->getId()) ? true : false;
        if ($exists) {
            $this->update($user);
            return $user;
        }
        $this->insert($user);
        return $user;
    }

    public function getById(string $id): ?User
    {
        return $this->first($id);
    }

    public function getByEmail(string $email): ?User
    {
        $users = $this->getAll([
            ['email', $email]
        ]);
        if (count($users) < 1) {
            return null;
        }
        return $users[0];
    }

    public function existsUserWithDocument(string $document_number): bool
    {
        $query = 'select * from '.$this->table.' where cpf = :cpf or cnpj = :cnpj';
        $params = [
            ':cpf' => $document_number,
            ':cnpj' => $document_number
        ];
        $users = $this->driver->executeSelectFromText($query, $params);
        return count($users) > 0;
    }

    public function existsUserWithEmail(string $email): bool
    {
        $query = 'select * from '.$this->table.' where email = :email';
        $params = [
            ':email' => $email,
        ];
        $users = $this->driver->executeSelectFromText($query, $params);
        return count($users) > 0;
    }

    public function getAll(array $condition = []): array
    {
        return $this->all($condition);
    }
}
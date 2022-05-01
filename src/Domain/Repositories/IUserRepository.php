<?php

namespace AppFinance\Domain\Repositories;

use AppFinance\Domain\Entities\User;

interface IUserRepository
{
    public function save(User $user): User;
    public function getById(string $id): ?User;
    public function getAll(): array;
    public function getByEmail(string $email): ?User;
    public function existsUserWithDocument(string $document_number): bool;
    public function existsUserWithEmail(string $email): bool;
}
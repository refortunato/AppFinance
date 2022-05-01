<?php

namespace AppFinance\Application\UseCases\GetUserByToken;

use AppFinance\Domain\Entities\User;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Protocols\Jwt;

class GetUserByToken
{
    private IUserRepository $user_repository;
    private Jwt $jwt;

    public function __construct(IUserRepository $user_repository, Jwt $jwt)
    {
        $this->user_repository = $user_repository;
        $this->jwt = $jwt;
    }

    public function execute(string $token): ?User
    {
        try {
           $token_decoded = $this->jwt->decrypt($token);
           $user = $this->user_repository->getById($token_decoded->id);
           return $user;
        } catch (\Exception $e) {
            return null;
        }
        
    }

}
<?php

namespace AppFinance\Application\UseCases\Login;

use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Protocols\Jwt;
use AppFinance\Protocols\PasswordHasher;
use AppFinance\Shared\Exceptions\LoginException;

class Login
{
    private IUserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private Jwt $jwt;

    public function __construct(
        IUserRepository $userRepository,
        PasswordHasher $passwordHasher,
        Jwt $jwt
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwt = $jwt;
    }

    public function execute(string $email, string $password): array
    {
        $user = $this->userRepository->getByEmail($email);
        if (empty($user)) {
            throw new LoginException("Login e ou Senha inválidos.");
        }
        if (! $this->passwordHasher->verify($password, $user->getHashedPassword())) {
            throw new LoginException("Login e ou Senha inválidos.");
        }
        $token = $this->jwt->encrypt(
            [
                'id' => $user->getId(),
                'user_type' => $user->getUserType()
            ]
        );
        return [
            'token' => $token
        ];
    }

}
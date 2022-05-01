<?php

namespace AppFinance\Application\UseCases\CreateCommonUser;

use AppFinance\Domain\Entities\CommonUser;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Domain\ValueObjects\DocumentFactory;
use AppFinance\Domain\ValueObjects\Email;
use AppFinance\Protocols\PasswordHasher;
use AppFinance\Shared\Validators\TextValidator;

class CreateCommonUser 
{
    private IUserRepository $user_repository;
    private PasswordHasher $passwordHasher;

    public function __construct(IUserRepository $user_repository, PasswordHasher $passwordHasher)
    {
        $this->user_repository = $user_repository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(CreateCommonUserInputDto $inputParams): CreateCommonUserOutputDto 
    {
        if ($inputParams->getPassword() !== $inputParams->getRepeatPassword()) {
            throw new \DomainException("Senha e Repetir senha não coincidem");
        }

        $hashed_password = $this->passwordHasher->hash($inputParams->getPassword());

        $commonUser = new CommonUser(
            '',
            new Email($inputParams->getEmail()),
            DocumentFactory::create($inputParams->getCpfCnpj()),
            $inputParams->getName(),
            $hashed_password
        );

        if ($this->user_repository->existsUserWithDocument($commonUser->getDocument()->getValue())) {
            throw new \DomainException("CPF/CNPJ já cadastrado");
        }
        if ($this->user_repository->existsUserWithEmail($commonUser->getEmail()->getEmailAddress())) {
            throw new \DomainException("E-mail já cadastrado.");
        }

        $this->user_repository->save($commonUser);

        return new CreateCommonUserOutputDto(
            $commonUser->getId(),
            $commonUser->getName(),
            $commonUser->getDocument()->getValue(),
            $commonUser->getDocument()->getType(),
            $commonUser->getEmail()->getEmailAddress()
        );
    }
}
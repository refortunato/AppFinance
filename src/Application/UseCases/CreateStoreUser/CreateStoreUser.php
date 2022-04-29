<?php

namespace AppFinance\Application\UseCases\CreateStoreUser;

use AppFinance\Domain\Entities\StoreUser;
use AppFinance\Domain\Repositories\IUserRepository;
use AppFinance\Domain\ValueObjects\DocumentFactory;
use AppFinance\Domain\ValueObjects\Email;
use AppFinance\Protocols\PasswordHasher;
use AppFinance\Shared\Validators\TextValidator;

class CreateStoreUser 
{
    private IUserRepository $user_repository;
    private PasswordHasher $passwordHasher;

    public function __construct(IUserRepository $user_repository, PasswordHasher $passwordHasher)
    {
        $this->user_repository = $user_repository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute(CreateStoreUserInputDto $inputParams): CreateStoreUserOutputDto 
    {
        TextValidator::validateOrException('E-mail', $inputParams->getEmail());
        TextValidator::validateOrException('Nome', $inputParams->getName());
        TextValidator::validateOrException('CPF/CNPJ', $inputParams->getCpfCnpj());
        TextValidator::validateOrException('Senha', $inputParams->getPassword(), ['min' => 8]);
        TextValidator::validateOrException('Repetir Senha', $inputParams->getRepeatPassword(), ['min' => 8]);

        if ($inputParams->getPassword() !== $inputParams->getRepeatPassword()) {
            throw new \DomainException("Senha e Repetir senha não coincidem");
        }

        $hashed_password = $this->passwordHasher->hash($inputParams->getPassword());

        $storeUser = new StoreUser(
            '',
            new Email($inputParams->getEmail()),
            DocumentFactory::create($inputParams->getCpfCnpj()),
            $inputParams->getName(),
            $hashed_password
        );

        if ($this->user_repository->existsUserWithDocument($inputParams->getCpfCnpj())) {
            throw new \DomainException("CPF/CNPJ já cadastrado");
        }
        if ($this->user_repository->existsUserWithEmail($inputParams->getEmail())) {
            throw new \DomainException("E-mail já cadastrado.");
        }

        $this->user_repository->save($storeUser);

        return new CreateStoreUserOutputDto(
            $storeUser->getId(),
            $storeUser->getName(),
            $storeUser->getDocument()->getValue(),
            $storeUser->getDocument()->getType(),
            $storeUser->getEmail()->getEmailAddress()
        );
    }
}
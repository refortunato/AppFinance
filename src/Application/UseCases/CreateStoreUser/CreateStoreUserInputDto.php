<?php

namespace AppFinance\Application\UseCases\CreateStoreUser;

class CreateStoreUserInputDto
{
    private ?string $name;
    private ?string $cpf_cnpj;
    private ?string $email;
    private ?string $password;
    private ?string $repeat_password;

    public function __construct(
        ?string $name,
        ?string $cpf_cnpj,
        ?string $email,
        ?string $password,
        ?string $repeat_password
    )
    {
        $this->name = $name;
        $this->cpf_cnpj = $cpf_cnpj;
        $this->email = $email;
        $this->password = $password;
        $this->repeat_password = $repeat_password;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCpfCnpj(): ?string
    {
        return $this->cpf_cnpj;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRepeatPassword(): ?string
    {
        return $this->repeat_password;
    }

}
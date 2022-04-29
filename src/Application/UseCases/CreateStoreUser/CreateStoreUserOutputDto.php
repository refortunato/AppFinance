<?php

namespace AppFinance\Application\UseCases\CreateStoreUser;

class CreateStoreUserOutputDto
{
    private ?string $user_id;
    private ?string $user_name;
    private ?string $document_number;
    private ?string $document_type;
    private ?string $email;

    public function __construct(
        string $user_id,
        ?string $user_name,
        ?string $document_number,
        ?string $document_type,
        ?string $email
    )
    {
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->document_number = $document_number;
        $this->document_type = $document_type;
        $this->email = $email;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function getDocumentNumber(): string
    {
        return $this->document_number;
    }

    public function getDocumentType(): string
    {
        return $this->document_type;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function mapToArray(): Array
    {
        return [
            'user_id' => $this->getUserId(),
            'user_name' => $this->getUserName(),
            'document_number' => $this->getDocumentNumber(),
            'document_type' => $this->getDocumentType(),
            'email' => $this->getEmail(),
        ];
    }
}
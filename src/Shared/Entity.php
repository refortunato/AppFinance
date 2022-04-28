<?php

namespace AppFinance\Shared;

use Ramsey\Uuid\Uuid;

abstract class Entity
{
    protected string $id;

    protected function __construct($id)
    {
        if (empty($id)) {
            $this->id = Uuid::uuid4()->toString();
        }
        else {
            $this->id = $id;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}
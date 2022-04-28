<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Shared\Entity;

class StoreUser extends Entity
{
    public function __construct(
        $id
    )
    {
        parent::__construct($id);
    }
}
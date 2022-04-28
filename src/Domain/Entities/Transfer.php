<?php

namespace AppFinance\Domain\Entities;

use AppFinance\Shared\Entity;

class Transfer extends Entity
{
    private \DateTime $run_date;
    private User $origin;
    private User $destiny;
    private float $value;

    public function __construct(
        $id,
        \DateTime $rud_date,
        User $origin,
        User $destiny,
        float $value
    )
    {
        parent::__construct($id);  
        $this->rud_date = $rud_date;
        $this->origin = $origin;
        $this->destiny = $destiny;
        $this->value = $value;
    }

    public function getRunDate(): \DateTime
    {
        return $this->run_date;
    }

    public function getOrigin (): User 
    { 
        return $this->origin;
    }

    public function getDestiny(): User
    { 
        return $this->destiny;
    }
    public function getValue(): float
    { 
        return $this->value;
    }
}
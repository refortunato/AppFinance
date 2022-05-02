<?php

namespace AppFinance\Domain\Events;

use AppFinance\Domain\Services\Transfer;
use AppFinance\Protocols\EmailSender;
use AppFinance\Protocols\Event;

class CompletedTransfer implements Event
{
    private Transfer $transfer;

    public function __construct(
        Transfer $transfer
    )
    {
        $this->transfer = $transfer;
    }

    public function getEventName(): string
    {
        return 'CompletedTransfer';
    }

    public function getTransfer(): Transfer
    {
        return $this->transfer;
    }
}
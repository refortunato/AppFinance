<?php

namespace AppFinance\Protocols;

interface Event
{
    public function getEventName(): string;
}
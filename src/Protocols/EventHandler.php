<?php

namespace AppFinance\Protocols;

interface EventHandler
{
    public function notify(Event $event);
}
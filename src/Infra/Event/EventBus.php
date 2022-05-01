<?php

namespace AppFinance\Infra\Event;

use AppFinance\Protocols\Event;
use AppFinance\Protocols\EventHandler;

class EventBus
{
    private $consumers = [];

    public function subscribe(string $event_name, EventHandler $eventHandler)
    {
        $this->consumers[] = ['event_name' => $event_name, 'event_handler' => $eventHandler];
    }
    
    public function publish(Event $event)
    {
        foreach ($this->consumers as $consumer) {
            if ($consumer['event_name'] === $event->getEventName()) {
                $consumer['event_handler']->notify($event);
            }
        }
    }
}
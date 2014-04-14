<?php

namespace mbfisher\Watch\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use mbfisher\Watch\EventDispatcher\Event\ExceptionEvent;

abstract class EventDispatcher extends BaseEventDispatcher
{
    public function dispatch($eventName, Event $event = null)
    {
        try {
            return parent::dispatch($eventName, $event);
        } catch (\Exception $ex) {
            if ($this->hasListeners('exception')) {
                return parent::dispatch('exception', new ExceptionEvent($ex, $event));
            } else {
                throw $ex;
            }
        }
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        parent::addListener($eventName, $listener, $priority);
        return $this;
    }
}

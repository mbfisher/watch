<?php

namespace mbfisher\Watch\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;
use mbfisher\Watch\EventDispatcher\Event\EventInterface;

class ExceptionEvent extends BaseEvent
{
    protected $exception;

    public function __construct(\Exception $ex, EventInterface $event)
    {
        $this->exception = $ex;
        $this->event = $event;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getEvent()
    {
        return $this->event;
    }
}

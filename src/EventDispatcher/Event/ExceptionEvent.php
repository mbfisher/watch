<?php

namespace mbfisher\Watch\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class ExceptionEvent extends BaseEvent
{
    protected $exception;

    public function __construct(\Exception $ex)
    {
        $this->exception = $ex;
    }

    public function getException()
    {
        return $this->exception;
    }
}

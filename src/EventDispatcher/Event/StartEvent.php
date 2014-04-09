<?php

namespace mbfisher\Watch\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class StartEvent extends BaseEvent
{
    public function __construct($path, $pattern = null)
    {
        $this->path = $path;
        $this->pattern = $pattern;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPattern()
    {
        return $this->pattern;
    }
}

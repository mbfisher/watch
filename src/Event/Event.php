<?php

namespace mbfisher\Watch\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent implements EventInterface
{
    protected $file;

    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}

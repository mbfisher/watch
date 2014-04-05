<?php

namespace mbfisher\Watch\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class InotifyEvent extends Event
{
    protected $mask;

    public function __construct(\SplFileInfo $file, $mask)
    {
        parent::__construct($file);
        $this->mask = $mask;
    }

    public function getMask()
    {
        return $this->mask;
    }
}

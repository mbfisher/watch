<?php

namespace mbfisher\Watch\EventDispatcher\Event;

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

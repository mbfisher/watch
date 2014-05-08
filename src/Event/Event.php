<?php

namespace mbfisher\Watch\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent implements EventInterface
{
    protected $file;

    public function __construct($path)
    {
        $this->file = $path instanceof \SplFileInfo ? $path : new \SplFileInfo($path);
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }
}

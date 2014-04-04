<?php

namespace mbfisher\Watch;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{
    protected $details;
    protected $target;

    public function __construct(array $details, $target)
    {
        $this->details = $details;
        $this->target = new \SplFileInfo($target);
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getMask()
    {
        return $this->details['mask'];
    }
}

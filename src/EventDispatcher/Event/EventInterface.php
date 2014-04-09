<?php

namespace mbfisher\Watch\EventDispatcher\Event;

interface EventInterface
{
    /**
     * @return SplFileInfo
     */
    public function getFile();
}

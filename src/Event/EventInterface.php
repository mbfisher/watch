<?php

namespace mbfisher\Watch\Event;

interface EventInterface
{
    /**
     * @return SplFileInfo
     */
    public function getFile();
}

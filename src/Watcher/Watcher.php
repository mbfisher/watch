<?php

namespace mbfisher\Watch\Watcher;

use mbfisher\Watch\EventDispatcher\EventDispatcher;
use mbfisher\Watch\Event\Event;

abstract class Watcher extends EventDispatcher implements WatcherInterface
{
    const EVENT_MODIFY = 'modify';
    const EVENT_ALL = 'all';

    protected function modify(\SplFileInfo $file)
    {
        return $this->dispatch(self::EVENT_MODIFY, new Event($file));
    }

    protected function all(\SplFileInfo $file)
    {
        return $this->dispatch(self::EVENT_ALL, new Event($file));
    }
}

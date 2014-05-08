<?php

namespace mbfisher\Watch\Watcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as Base;

interface WatcherInterface extends Base
{
    public function start();
    public function stop();
}

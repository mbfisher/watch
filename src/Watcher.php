<?php

namespace mbfisher\Watch;

use mbfisher\Watch\EventDispatcher\EventDispatcherInterface;
use mbfisher\Watch\EventDispatcher\InotifyEventDispatcher;
use mbfisher\Watch\EventDispatcher\IteratorEventDispatcher;

class Watcher
{
    protected $stop;
    
    public function __construct($path, $pattern = null)
    {
        if (!function_exists('inotify_init')) {
            $this->dispatcher = new InotifyEventDispatcher($path, $pattern);
        } else {
            $this->dispatcher = new IteratorEventDispatcher($path, $pattern);
        }
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
    }

    public function on($event, callable $callback)
    {
        $this->dispatcher->addListener($event, $callback);
        return $this;
    }

    public function start()
    {
        $this->dispatcher->start();
        return $this;
    }

    public function stop()
    {
        $this->dispatcher->stop();
        return $this;
    }
}

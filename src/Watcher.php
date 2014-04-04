<?php

namespace mbfisher\Watch;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Watcher extends EventDispatcher
{
    protected $stop;

    public function __construct($path, $events = IN_ALL_EVENTS)
    {
        $this->path = $path;
        $this->events = IN_ALL_EVENTS;
    }

    public function start()
    {
        $this->stop = false;
        $fd = inotify_init();
        $wd = inotify_add_watch($fd, $this->path, $this->events);

        $read = [$fd];
        $write = null;
        $except = null;
        stream_select($read, $write, $except, 0);
        stream_set_blocking($fd, 0);

        while (true) {
            if ($this->stop) {
                inotify_rm_watch($fd, $wd);
                return fclose($fd);
            }

            if ($events = inotify_read($fd)) {
                foreach ($events as $details) {
                    if ($details['name']) {
                        $target = rtrim($this->path, '/').'/'.$details['name'];
                    } else {
                        $target = $this->path;
                    }

                    $event = new Event($details, $target);

                    $this->dispatch($details['mask'], $event);
                    $this->dispatch(IN_ALL_EVENTS, $event);
                }
            }
        }
    }

    public function stop()
    {
        $this->stop = true;
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        parent::addListener($eventName, $listener, $priority);
        return $this;
    }
}

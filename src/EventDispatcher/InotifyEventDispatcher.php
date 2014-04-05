<?php

namespace mbfisher\Watch\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use mbfisher\Watch\Event\InotifyEvent as Event;

class InotifyEventDispatcher extends EventDispatcher implements EventDispatcherInterface
{
    protected $stop;

    public function __construct($path, $pattern = null)
    {
        $this->path = $path;
    }

    public function start()
    {
        $this->stop = false;
        $fd = inotify_init();
        $wd = inotify_add_watch($fd, $this->path, IN_ALL_EVENTS);

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

                    $event = new Event(new \SplFileInfo($target), $details['mask']);

                    switch (true) {
                        case $details['mask'] & IN_MODIFY:
                            $this->dispatch('modify', $event);
                            break;
                    }

                    $this->dispatch('all', $event);
                }
            }
        }
    }

    public function stop()
    {
        $this->stop = true;
    }
}

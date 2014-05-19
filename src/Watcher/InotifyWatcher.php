<?php

namespace mbfisher\Watch\Watcher;

class InotifyWatcher extends Watcher
{
    protected $stop;
    protected $sleep = 50000;

    public function __construct($path, $pattern = null)
    {
        $this->path = $path;
        $this->pattern = null;
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

                    $file = new \SplFileInfo($target);
                    switch (true) {
                        case $details['mask'] & IN_MODIFY:
                            $this->modify($file);
                            break;
                    }

                    $this->all($file);
                }
            }

            usleep($this->sleep);
        }
    }

    public function stop()
    {
        $this->stop = true;
    }
}

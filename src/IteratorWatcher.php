<?php

namespace mbfisher\Watch;

use mbfisher\Watch\EventDispatcher\EventDispatcher;
use mbfisher\Watch\EventDispatcher\Event\Event;
use mbfisher\Watch\EventDispatcher\Event\StartEvent;

class IteratorWatcher extends EventDispatcher implements WatcherInterface
{
    protected $files = [];
    protected $stop;

    public function __construct($path, $pattern = null)
    {
        $this->path = $path;
        $this->pattern = $pattern;
    }

    public function start()
    {
        if (is_dir($this->path)) {
            return $this->startDirectory();
        } elseif (is_file($this->path)) {
            return $this->startFile();
        } else {
            throw new \InvalidArgumentException("$this->path does not exist");
        }
    }

    public function startDirectory()
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
            $this->path
        ));

        $this->dispatch('start', new StartEvent($this->path, $this->pattern));

        $this->files = [];
        $this->stop = false;
        while (true) {
            if ($this->stop) {
                return;
            }

            foreach ($iterator as $file) {
                $path = $file->getPathname();

                if ($this->pattern && !preg_match($this->pattern, $path)) {
                    continue;
                }

                $this->trigger($file);
            }
        }
    }

    public function startFile()
    {
        $this->dispatch('start', new StartEvent($this->path, $this->pattern));

        while (true) {
            if ($this->stop) {
                return;
            }

            $this->trigger(new \SplFileInfo($this->path));
        }
    }

    public function trigger(\SplFileInfo $file)
    {
        $path = $file->getPathname();
        $event = new Event($file);

        clearstatcache(true, $path);
        $mtime = $file->getMTime();

        $dispatch = false;
        if (isset($this->files[$path])) {
            $previous = $this->files[$path];

            if ($mtime > $previous) {
                $dispatch = true;
                $this->dispatch('modify', $event);
            }
        }

        if ($dispatch) {
            $this->dispatch('all', $event);
        }

        $this->files[$path] = $mtime;
    }

    public function stop()
    {
        $this->stop = true;
    }
}

<?php

namespace mbfisher\Watch\Watcher;

class IteratorWatcher extends Watcher
{
    protected $files = [];
    protected $stop;
    protected $include;
    protected $exclude;
    protected $sleep = 50000;

    public function __construct($path, $include = null, $exclude = null)
    {
        $this->path = $path;
        $this->include = $include;
        $this->exclude = $exclude;
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

        $this->files = [];
        $this->stop = false;
        while (true) {
            if ($this->stop) {
                return;
            }

            foreach ($iterator as $file) {
                $path = $file->getPathname();

                if ($this->include && !preg_match($this->include, $path)) {
                    continue;
                }

                if ($this->exclude && preg_match($this->exclude, $path)) {
                    continue;
                }

                $this->checkFile($file);
            }

            usleep($this->sleep);
        }
    }

    public function startFile()
    {
        while (true) {
            if ($this->stop) {
                return;
            }

            $this->checkFile(new \SplFileInfo($this->path));

            usleep($this->sleep);
        }
    }

    public function checkFile(\SplFileInfo $file)
    {
        $path = $file->getPathname();

        clearstatcache(true, $path);
        $mtime = $file->getMTime();

        $dispatch = false;
        if (isset($this->files[$path])) {
            $previous = $this->files[$path];

            if ($mtime > $previous) {
                $dispatch = true;
                $this->modify($file);
            }
        }

        if ($dispatch) {
            $this->all($file);
        }

        $this->files[$path] = $mtime;
    }

    public function stop()
    {
        $this->stop = true;
    }
}

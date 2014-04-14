<?php

namespace mbfisher\Watch\EventDispatcher\Event;

class InotifyEvent extends Event
{
    protected $mask;

    public function __construct(\SplFileInfo $file, $mask)
    {
        parent::__construct($file);
        $this->mask = $mask;
    }

    public function getMask()
    {
        return $this->mask;
    }

    public function getMaskName()
    {
        foreach (get_defined_constants() as $name => $value) {
            if (strpos($name, 'IN_') === 0 && $value == $this->mask) {
                return $name;
            }
        }

        return false;
    }
}

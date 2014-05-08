<?php

namespace mbfisher\Watch\Watcher;

use mbfisher\Watch\Test\TriggerEventTrait;

class InotifyWatcherTest extends \PHPUnit_Framework_TestCase
{
    use TriggerEventTrait;

    public function testWatch()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $dispatcher = new InotifyWatcher($tmp);
        $this->triggerEvent('all', $dispatcher, function () use ($tmp) {
            touch($tmp);
        });

        sleep(1);
        unlink($tmp);
    }
}

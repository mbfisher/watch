<?php

namespace mbfisher\Watch\EventDispatcher;

use mbfisher\Watch\Test\TriggerEventTrait;

class InotifyEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    use TriggerEventTrait;

    public function testWatch()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $dispatcher = new InotifyEventDispatcher($tmp);
        $this->triggerEvent('all', $dispatcher, function () use ($tmp) {
            touch($tmp);
        });

        sleep(1);
        unlink($tmp);
    }
}

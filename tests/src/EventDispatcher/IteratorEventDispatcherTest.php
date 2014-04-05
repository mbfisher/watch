<?php

namespace mbfisher\Watch\EventDispatcher;

use mbfisher\Watch\Test\TriggerEventTrait;

class IteratorEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    use TriggerEventTrait;

    public function testFile()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $dispatcher = new IteratorEventDispatcher($tmp);
        $this->triggerEvent('all', $dispatcher, function () use ($tmp) {
            touch($tmp);
        });

        sleep(1);
        unlink($tmp);
    }

    public function testDir()
    {
        $dir = sys_get_temp_dir().'/watch'.time();
        $tmp = "$dir/foo";
        mkdir($dir);

        $dispatcher = new IteratorEventDispatcher($dir);
        $this->triggerEvent('all', $dispatcher, function () use ($tmp) {
            touch($tmp);
        });

        sleep(1);
        unlink($tmp);
    }
}

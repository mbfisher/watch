<?php

namespace mbfisher\Watch;

use mbfisher\Watch\Test\TriggerEventTrait;

class IteratorWatcherTest extends \PHPUnit_Framework_TestCase
{
    use TriggerEventTrait;

    public function testFile()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $dispatcher = new IteratorWatcher($tmp);
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

        $dispatcher = new IteratorWatcher($dir);
        $this->triggerEvent('all', $dispatcher, function () use ($tmp) {
            touch($tmp);
        });

        sleep(1);
        unlink($tmp);
    }
}

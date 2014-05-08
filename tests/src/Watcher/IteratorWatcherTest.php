<?php

namespace mbfisher\Watch\Watcher;

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
        `rm -rf $dir`;
    }

    public function testModify()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $dispatcher = new IteratorWatcher($tmp);
        $this->triggerEvent('modify', $dispatcher, function () use ($tmp) {
            file_put_contents($tmp, 'foo');
        });

        sleep(1);
        unlink($tmp);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsOnBadPath()
    {
        (new IteratorWatcher('nope'))->start();
    }

    /*public function testInclude()
    {
        $dir = sys_get_temp_dir().'/watch'.time();
        mkdir($dir);
        touch("$dir/foo");

        $dispatcher = new IteratorWatcher($dir, '/foo/');
        $this->triggerEvent('all', $dispatcher, function () use ($dir) {
            touch("$dir/bar");
        }, false);

        sleep(1);
        `rm -rf $dir`;
    }*/
}

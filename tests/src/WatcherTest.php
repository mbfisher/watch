<?php

namespace mbfisher\Watch;

class WatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testFsWatch()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'watch');
        $called = false;

        $pid = pcntl_fork();
        if ($pid == -1) {
            die('could not fork');
        } elseif ($pid) {
            (new Watcher($tmp))
                ->addListener(IN_ALL_EVENTS, function ($event, $name, $dispatcher) use (&$called) {
                    $called = true;
                    $dispatcher->stop();
                })
                ->start();
        } else {
            sleep(1);
            touch($tmp);
            die();
        }

        $this->assertTrue($called);
    }
}

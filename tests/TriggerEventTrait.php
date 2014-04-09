<?php

namespace mbfisher\Watch\Test;

use mbfisher\Watch\WatcherInterface;

trait TriggerEventTrait
{
    public function triggerEvent($event, WatcherInterface $watcher, callable $trigger)
    {
        $called = false;

        $pid = pcntl_fork();
        if ($pid == -1) {
            die('could not fork');
        } elseif ($pid) {
            $watcher->addListener($event, function ($event, $name, $watcher) use (&$called) {
                $called = true;
                $watcher->stop();
            });
            $watcher->start();
        } else {
            sleep(1);
            call_user_func($trigger);
            die();
        }

        $this->assertTrue($called);
    }
}

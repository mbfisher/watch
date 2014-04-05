<?php

namespace mbfisher\Watch\Test;

use mbfisher\Watch\EventDispatcher\EventDispatcherInterface;

trait TriggerEventTrait
{
    public function triggerEvent($event, EventDispatcherInterface $dispatcher, callable $trigger)
    {
        $called = false;

        $pid = pcntl_fork();
        if ($pid == -1) {
            die('could not fork');
        } elseif ($pid) {
            $dispatcher->addListener($event, function ($event, $name, $dispatcher) use (&$called) {
                $called = true;
                $dispatcher->stop();
            });
            $dispatcher->start();
        } else {
            sleep(1);
            call_user_func($trigger);
            die();
        }

        $this->assertTrue($called);
    }
}

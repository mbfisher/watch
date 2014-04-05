<?php

namespace mbfisher\Watch\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as Base;

interface EventDispatcherInterface extends Base
{
    public function start();
    public function stop();
}

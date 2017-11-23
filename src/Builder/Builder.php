<?php

namespace Andaniel05\ComposedViews\Builder;

use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};

class Builder implements BuilderInterface
{
    protected $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function onTag(string $tag, callable $listener)
    {
    }
}

<?php

namespace Andaniel05\ComposedViews\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface BuilderInterface
{
    public function setDispatcher(EventDispatcherInterface $dispatcher);

    public function getDispatcher(): EventDispatcherInterface;

    public function onTag(string $tag, callable $listener);
}

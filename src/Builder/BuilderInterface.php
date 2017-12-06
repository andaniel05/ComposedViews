<?php

namespace Andaniel05\ComposedViews\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
interface BuilderInterface
{
    public function setDispatcher(EventDispatcherInterface $dispatcher);

    public function getDispatcher(): EventDispatcherInterface;

    public function onTag(string $tag, callable $listener);

    public function build(string $xml);
}

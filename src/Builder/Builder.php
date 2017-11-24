<?php

namespace Andaniel05\ComposedViews\Builder;

use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;

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
        $this->dispatcher->addListener($tag, $listener);
    }

    public function build(string $xml)
    {
        $element = new \SimpleXMLElement($xml);
        $event = new BuilderEvent($element, $this);

        $this->dispatcher->dispatch($element->getName(), $event);

        return $event->getEntity();
    }
}

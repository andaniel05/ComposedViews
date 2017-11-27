<?php

namespace Andaniel05\ComposedViews\Builder;

use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;
use Andaniel05\ComposedViews\Component\ComponentInterface;

class Builder implements BuilderInterface
{
    protected $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher;

        $this->onEntity(function ($event) {

            $component = $event->getEntity();
            if ( ! $component instanceOf ComponentInterface) {
                return;
            }

            $element = $event->getXMLElement();

            $id = (string) $element['id'];
            if ( ! $id) {
                $id = uniqid('comp');
            }

            $component->setId($id);
        });
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
        $this->dispatcher->addListener("{$tag}__tag", $listener);
    }

    public function onTagPopulation(string $tag, callable $listener)
    {
        $this->dispatcher->addListener("{$tag}__population", $listener);
    }

    public function onEntity(callable $listener)
    {
        $this->dispatcher->addListener('entity', $listener);
    }

    public function build(string $xml)
    {
        $element = new \SimpleXMLElement($xml);
        $tag = $element->getName();

        $event = new BuilderEvent($element, $this);
        $this->dispatcher->dispatch("{$tag}__tag", $event);

        $entity = $event->getEntity();

        if ($entity) {

            $this->dispatcher->dispatch('entity', $event);

            if ($this->dispatcher->hasListeners("{$tag}__population")) {
                $this->dispatcher->dispatch("{$tag}__population", $event);
            } else {

                foreach ($element->children() as $childElement) {
                    $child = $this->build($childElement->asXML());
                    if ($child instanceof ComponentInterface) {
                        $entity->addChild($child);
                    }
                }

            }
        }

        return $event->getEntity();
    }
}

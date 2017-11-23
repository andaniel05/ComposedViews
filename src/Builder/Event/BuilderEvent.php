<?php

namespace Andaniel05\ComposedViews\Builder\Event;

use Symfony\Component\EventDispatcher\Event;

class BuilderEvent extends Event
{
    protected $element;
    protected $entity;

    public function __construct(\SimpleXMLElement $element)
    {
        $this->element = $element;
    }

    public function getXMLElement(): \SimpleXMLElement
    {
        return $this->element;
    }

    public function getNode(): \SimpleXMLElement
    {
        return $this->element;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}

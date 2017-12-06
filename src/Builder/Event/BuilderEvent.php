<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Builder\Event;

use Symfony\Component\EventDispatcher\Event;
use Andaniel05\ComposedViews\Builder\BuilderInterface;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class BuilderEvent extends Event
{
    protected $element;
    protected $entity;
    protected $builder;

    public function __construct(\SimpleXMLElement $element, BuilderInterface $builder)
    {
        $this->element = $element;
        $this->builder = $builder;
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

    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}

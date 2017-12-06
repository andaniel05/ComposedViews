<?php

namespace Andaniel05\ComposedViews\Tests\Builder\Event;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\BuilderInterface;
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class BuilderEventTest extends TestCase
{
    public function setUp()
    {
        $this->element = new \SimpleXMLElement('<xml></xml>');
        $this->builder = $this->createMock(BuilderInterface::class);
        $this->event = new BuilderEvent($this->element, $this->builder);
    }

    public function testGetXMLElementReturnArgument()
    {
        $this->assertEquals($this->element, $this->event->getXMLElement());
    }

    public function testGetEntityReturnNullByDefault()
    {
        $this->assertNull($this->event->getEntity());
    }

    public function testSetEntity()
    {
        $entity = new \stdClass;
        $this->event->setEntity($entity);

        $this->assertEquals($entity, $this->event->getEntity());
    }

    public function testGetNodeIsAliasToGetXMLElement()
    {
        $this->assertEquals($this->element, $this->event->getNode());
    }

    public function testGetBuilderReturnBuilderArgument()
    {
        $this->assertEquals($this->builder, $this->event->getBuilder());
    }
}

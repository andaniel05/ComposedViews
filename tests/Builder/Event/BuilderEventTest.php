<?php

namespace Andaniel05\ComposedViews\Tests\Builder\Event;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\Event\BuilderEvent;

class BuilderEventTest extends TestCase
{
    public function setUp()
    {
        $this->element = new \SimpleXMLElement('<xml></xml>');
        $this->event = new BuilderEvent($this->element);
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
}

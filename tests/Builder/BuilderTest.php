<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Builder\{BuilderInterface, Builder};
use Symfony\Component\EventDispatcher\{EventDispatcherInterface, EventDispatcher};

class BuilderTest extends TestCase
{
    public function setUp()
    {
        $this->builder = new Builder;
        $this->builder->onTag('component', function ($event) {
            $component = new Component(uniqid());
            $event->setEntity($component);
        });
    }

    public function testIsInstanceOfBuilderInterface()
    {
        $this->assertInstanceOf(BuilderInterface::class, $this->builder);
    }

    public function testHasAnDispatcherByDefault()
    {
        $this->assertInstanceOf(
            EventDispatcherInterface::class, $this->builder->getDispatcher()
        );
    }

    public function testBuildReturnAnEntityCreatedByTheUserInsideTheEvent()
    {
        $tag = uniqid('tag');
        $xml = "<{$tag}></{$tag}>";
        $entity = new \stdClass;

        $this->builder->onTag($tag, function ($event) use ($entity) {
            $event->setEntity($entity);
        });

        $this->assertEquals($entity, $this->builder->build($xml));
    }

    public function testEntityEventIsTriggeredAfterTagEvent()
    {
        $tag = uniqid('tag');
        $xml = "<{$tag}></{$tag}>";
        $attr = uniqid('attr');
        $value = uniqid();

        $this->builder->onTag($tag, function ($event) {
            $event->setEntity(new \stdClass);
        });

        $this->builder->onEntity(function ($event) use ($attr, $value) {
            $entity = $event->getEntity();
            $entity->{$attr} = $value;
        });

        $entity = $this->builder->build($xml);
        $this->assertInstanceOf(\stdClass::class, $entity);
        $this->assertEquals($value, $entity->{$attr});
    }

    public function testEntityEventIsNotTriggeredIfOnTheTagEventHasBeenNotCreatedAnEntity()
    {
        $tag = uniqid('tag');
        $xml = "<{$tag}></{$tag}>";
        $executed = false;

        $this->builder->onTag($tag, function ($event) {});

        $this->builder->onEntity(function ($event) use (&$executed) {
            $executed = true;
        });

        $this->builder->build($xml);
        $this->assertFalse($executed);
    }

    public function testPopulationEventIsTriggeredAfterTagEvent()
    {
        $tag = uniqid('tag');
        $xml = "<{$tag}></{$tag}>";
        $attr = uniqid('attr');
        $value = uniqid();

        $this->builder->onTag($tag, function ($event) {
            $event->setEntity(new \stdClass);
        });

        $this->builder->onTagPopulation($tag, function ($event) use ($attr, $value) {
            $entity = $event->getEntity();
            $entity->{$attr} = $value;
        });

        $entity = $this->builder->build($xml);
        $this->assertInstanceOf(\stdClass::class, $entity);
        $this->assertEquals($value, $entity->{$attr});
    }

    public function testPopulationEventIsNotTriggeredIfOnTheTagEventHasBeenNotCreatedAnEntity()
    {
        $tag = uniqid('tag');
        $xml = "<{$tag}></{$tag}>";
        $executed = false;

        $this->builder->onTag($tag, function ($event) {
            $event->setEntity(new \stdClass);
        });

        $this->builder->onEntity(function ($event) {
            $event->setEntity(null);
        });

        $this->builder->onTagPopulation($tag, function ($event) use (&$executed) {
            $executed = true;
        });

        $this->assertFalse($executed);
    }

    public function testTheComponentsHasIdEqualToIdAttribute()
    {
        $id = uniqid('comp');
        $xml = "<component id=\"{$id}\"></component>";

        $component = $this->builder->build($xml);

        $this->assertEquals($id, $component->getId());
    }

    public function testTheComponentHasDefaultIdIfIdAttributeIsOmit()
    {
        $xml = "<component></component>";

        $component = $this->builder->build($xml);

        $this->assertStringStartsWith('comp', $component->getId());
    }

    public function testTheComponentHasDefaultIdIfIdAttributeIsEmpty()
    {
        $xml = "<component id=\"\"></component>";

        $component = $this->builder->build($xml);

        $this->assertStringStartsWith('comp', $component->getId());
    }

    public function testBuiltComponentesHasNestedChildren()
    {
        $xml = <<<XML
<component id="parent">
    <component id="child"></component>
</component>
XML;

        $parent = $this->builder->build($xml);
        $child = $parent->getChild('child');

        $this->assertEquals($parent, $child->getParent());
    }

    public function testBuiltComponentesHasNestedChildren1()
    {
        $xml = <<<XML
<component id="parent">
    <component id="child1">
        <component id="child2"></component>
    </component>
</component>
XML;

        $parent = $this->builder->build($xml);
        $child1 = $parent->getChild('child1');
        $child2 = $child1->getChild('child2');

        $this->assertEquals($parent, $child1->getParent());
        $this->assertEquals($child1, $child2->getParent());
    }

    public function testTheLogicOfPopulationMayBeChanged()
    {
        $this->builder->onTagPopulation('component', function ($event) {});
        $xml = <<<XML
<component id="parent">
    <component id="child1">
        <component id="child2"></component>
    </component>
</component>
XML;

        $parent = $this->builder->build($xml);
        $this->assertEmpty($parent->getChildren());
    }
}

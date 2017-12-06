<?php

namespace Andaniel05\ComposedViews\Tests\Component;

use Andaniel05\ComposedViews\Component\AbstractComponent;
use Andaniel05\ComposedViews\Component\ComponentTreeTrait;
use PHPUnit\Framework\TestCase;

class ComponentTreeTraitTest extends TestCase
{
    public function setUp()
    {
        $this->trait = $this->getMockForTrait(ComponentTreeTrait::class);

        $this->comp1 = $this->getMockForAbstractClass(
            AbstractComponent::class,
            ['comp1']
        );
        $this->comp2 = $this->getMockForAbstractClass(
            AbstractComponent::class,
            ['comp2']
        );
        $this->comp3 = $this->getMockForAbstractClass(
            AbstractComponent::class,
            ['comp3']
        );
        $this->comp4 = $this->getMockForAbstractClass(
            AbstractComponent::class,
            ['comp4']
        );
        $this->comp5 = $this->getMockForAbstractClass(
            AbstractComponent::class,
            ['comp5']
        );
    }

    public function setComponents(array $components)
    {
        $closure = function () use ($components) {
            $this->components = $components;
        };

        $closure->call($this->trait);
    }

    public function testTraverse1()
    {
        $this->setComponents([
            'comp1' => $this->comp1,
            'comp2' => $this->comp2,
            'comp3' => $this->comp3,
        ]);

        $iter = $this->trait->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
    }

    public function testTraverse2()
    {
        $this->comp1->addChild($this->comp2);
        $this->comp2->addChild($this->comp3);
        $this->comp3->addChild($this->comp4);
        $this->comp4->addChild($this->comp5);

        $this->setComponents([
            'comp1' => $this->comp1,
        ]);

        $iter = $this->trait->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp4, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp5, $iter->current());
    }

    public function testTraverse3()
    {
        $this->comp1->addChild($this->comp3);
        $this->comp1->addChild($this->comp4);
        $this->comp2->addChild($this->comp5);

        $this->setComponents([
            'comp1' => $this->comp1,
            'comp2' => $this->comp2,
        ]);

        $iter = $this->trait->traverse();

        $this->assertEquals($this->comp1, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp3, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp4, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp2, $iter->current());
        $iter->next();
        $this->assertEquals($this->comp5, $iter->current());
    }

    public function testGetComponent_ReturnNullWhenComponentDoNotExists()
    {
        $this->assertNull($this->trait->getComponent(uniqid()));
    }

    public function testGetComponent_ReturnRootComponents()
    {
        $id = uniqid();
        $component = $this->getMockForAbstractClass(AbstractComponent::class, [$id]);

        $this->setComponents([$id => $component]);

        $this->assertEquals($component, $this->trait->getComponent($id));
    }

    public function testGetComponent_Case1()
    {
        $this->comp1->addChild($this->comp2);

        $this->setComponents(['comp1' => $this->comp1]);

        $this->assertEquals($this->comp2, $this->trait->getComponent('comp1 comp2'));
    }

    public function testGetComponent_Case2()
    {
        $this->comp1->addChild($this->comp2);

        $this->setComponents(['comp1' => $this->comp1]);

        $this->assertEquals($this->comp2, $this->trait->getComponent('comp2'));
    }

    public function testGetComponent_Case3()
    {
        $this->comp1->addChild($this->comp2);
        $this->comp2->addChild($this->comp3);

        $this->setComponents(['comp1' => $this->comp1]);

        $this->assertEquals($this->comp3, $this->trait->getComponent('comp1 comp3'));
    }
}

<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    AbstractComposedComponent, ComponentContainerTrait};

trait ComponentContainerTraitTests
{
    public function getComponentContainerMock()
    {
        $container = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor();
        $container = $this->assumeMock($this->getTestClass(), $container);

        return $container;
    }

    public function testGetAllComponentsReturnAnEmptyArrayByDefault()
    {
        $container = $this->getComponentContainerMock();

        $this->assertEquals([], $container->getAllComponents());
    }

    public function testGetComponentReturnNullIfComponentNotExists()
    {
        $container = $this->getComponentContainerMock();

        $this->assertNull($container->getComponent('component1'));
    }

    public function insertTwoComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component1->method('getId')->willReturn('component1');
        $this->component2 = $this->createMock(AbstractComponent::class);
        $this->component2->method('getId')->willReturn('component2');

        $this->container = $this->getComponentContainerMock();
        $this->container->addComponent($this->component1);
        $this->container->addComponent($this->component2);
    }

    public function testGetComponentReturnTheComponentIfExists()
    {
        $this->insertTwoComponents();

        $this->assertSame(
            $this->component1,
            $this->container->getComponent('component1')
        );
    }

    public function testGetAllComponentsReturnAnArrayWithAllInsertedComponents()
    {
        $this->insertTwoComponents();

        $expected = [
            'component1' => $this->component1,
            'component2' => $this->component2,
        ];

        $this->assertEquals($expected, $this->container->getAllComponents());
    }

    public function testDropComponentRemoveTheComponentWhenExists()
    {
        $this->insertTwoComponents();

        $this->container->dropComponent('component2');

        $this->assertEquals(
            ['component1' => $this->component1],
            $this->container->getAllComponents()
        );
    }

    public function testExistsComponentReturnFalseWhenComponentNotExists()
    {
        $this->insertTwoComponents();

        $this->assertFalse($this->container->existsComponent('component5'));
    }

    public function testExistsComponentReturnTrueWhenComponentExists()
    {
        $this->insertTwoComponents();

        $this->assertTrue($this->container->existsComponent('component1'));
        $this->assertTrue($this->container->existsComponent('component2'));
    }

    public function initializeNestedComponents()
    {
        $container = $this->getComponentContainerMock();

        $component1 = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['component1'])
            ->getMockForAbstractClass();

        $component2 = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['component2'])
            ->getMockForAbstractClass();

        $component3 = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['component3'])
            ->getMockForAbstractClass();

        $component4 = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['component4'])
            ->getMockForAbstractClass();

        $component5 = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['component5'])
            ->getMockForAbstractClass();

        $component1->addComponent($component2);
        $component2->addComponent($component3);
        $component4->addComponent($component5);

        $container->addComponent($component1);
        $container->addComponent($component4);

        $this->container  = $container;
        $this->component1 = $component1;
        $this->component2 = $component2;
        $this->component3 = $component3;
        $this->component4 = $component4;
        $this->component5 = $component5;
    }

    public function testGetComponentSearchTheComponentInAllTheTree()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component2,
            $this->container->getComponent('component2')
        );
        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component3')
        );
        $this->assertSame(
            $this->component5,
            $this->container->getComponent('component5')
        );
    }

    public function testGetComponentWhenIdIsComplex1()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component5,
            $this->container->getComponent('component4 component5')
        );
    }

    public function testGetComponentWhenIdIsComplex2()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component1 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex3()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component1 component2 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex4()
    {
        $this->initializeNestedComponents();

        $this->assertSame(
            $this->component3,
            $this->container->getComponent('component2 component3')
        );
    }

    public function testGetComponentWhenIdIsComplex5()
    {
        $this->initializeNestedComponents();

        $this->assertNull(
            $this->container->getComponent('component1 component5')
        );
    }

    public function insertChildComponent()
    {
        $this->container = $this->getComponentContainerMock();
        $this->child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['child'])
            ->getMockForAbstractClass();

        $this->container->addComponent($this->child);
    }

    public function testAddComponentRegisterToItSelfAsParentInTheChild()
    {
        if ($this->getTestClass() == ComponentContainerTrait::class) {
            $this->markTestSkipped();
        }

        $this->insertChildComponent();

        $this->assertSame($this->container, $this->child->getParent());
    }

    public function testDropComponentSetNullAsParentInTheChild()
    {
        $this->insertChildComponent();

        $this->container->dropComponent('child');

        $this->assertNull($this->child->getParent());
    }
}
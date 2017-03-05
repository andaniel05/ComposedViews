<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Component\AbstractComponent;

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
    }
}
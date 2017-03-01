<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    ComponentContainerTrait, ComponentCollection};

class ComponentContainerTraitTest extends TestCase
{
    public function setUp()
    {
        $this->trait = $this->getMockForTrait(ComponentContainerTrait::class);
    }

    public function testComponents_IsNullByDefault()
    {
        $this->assertAttributeEquals(null, 'components', $this->trait);
    }

    public function testComponents_IsInstanceOfComponentCollectionAfterInitialization()
    {
        $this->trait->initialize();

        $this->assertAttributeInstanceOf(
            ComponentCollection::class, 'components', $this->trait
        );
    }

    public function testIsInitialized_ReturnFalseByDefault()
    {
        $this->assertAttributeEquals(null, 'components', $this->trait);
        $this->assertFalse($this->trait->isInitialized());
    }

    public function testIsInitialized_ReturnTrueWhenComponentsAttributeNotIsNull()
    {
        $this->trait->initialize();

        $this->assertAttributeInstanceOf(
            ComponentCollection::class, 'components', $this->trait
        );
        $this->assertTrue($this->trait->isInitialized());
    }

    // public function getMockForInitializationChecks()
    // {
    //     $trait = $this->getMockBuilder(ComponentContainerTrait::class)
    //         ->setMethods(['initialize'])
    //         ->getMockForTrait();
    //     $trait->expects($this->once())
    //         ->method('initialize');

    //     return $trait;
    // }

    // public function testGetAllComponents_InvokeTheInitialization()
    // {
    //     $trait = $this->getMockForInitializationChecks();

    //     $trait->getAllComponents();
    // }

    // public function testInsertComponent_InvokeTheInitialization()
    // {
    //     $trait = $this->getMockForInitializationChecks();

    //     $dummy = $this->createMock(AbstractComponent::class);

    //     $trait->insertComponent('dummy', $dummy);
    // }

    // public function testInitializationIsOnlyOnce()
    // {
    // }

    // public function testGetAllComponents_ReturnOtherInstanceOfComponentsAttribute()
    // {
    //     $components = $this->trait->getAllComponents();

    //     $this->assertAttributeNotSame(
    //         $components, 'components', $this->trait
    //     );
    // }

    // public function testGetAllComponents_ReturnAnEmptyComponentCollectionByDefault()
    // {
    //     $components = $this->trait->getAllComponents();

    //     $this->assertInstanceOf(();
    // }

    // public function testGetComponent_ReturnNullIfComponentNotExists()
    // {
    //     $this->assertNull($this->trait->getComponent('component1'));
    // }

    // public function insertTwoComponents()
    // {
    //     $this->component1 = $this->createMock(AbstractComponent::class);
    //     $this->component2 = $this->createMock(AbstractComponent::class);

    //     $this->trait->insertComponent('component1', $this->component1);
    //     $this->trait->insertComponent('component2', $this->component2);
    // }

    // public function testGetAllComponents_ReturnACollectionWithAllInsertedComponents()
    // {
    //     $this->insertTwoComponents();

    //     $components = $this->trait->getAllComponents();

    //     $this->assertSame($this->component1, $components['component1']);
    //     $this->assertSame($this->component2, $components['component2']);
    // }

    // public function testGetComponent_ReturnTheComponentWhenExists()
    // {
    //     $this->insertTwoComponents();

    //     $this->assertSame(
    //         $this->component1,
    //         $this->trait->getComponent('component1')
    //     );
    // }
}
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

    public function testIsInitialized_ReturnFalseByDefault()
    {
        $this->assertFalse($this->trait->isInitialized());
    }

    public function testGetAllComponents_ReturnAnEmptyComponentCollectionByDefault()
    {
        $this->assertEmpty($this->trait->getAllComponents());
    }

    public function testComponents_IsNullByDefault()
    {
        $this->assertAttributeEquals(null, 'components', $this->trait);
    }

    public function testInitialize_BuildNewComponentCollection()
    {
        $this->trait->initialize();

        $this->assertAttributeInstanceOf(
            ComponentCollection::class, 'components', $this->trait
        );
        $this->assertAttributeEmpty('components', $this->trait);
    }

    public function getMock1()
    {
        $trait = $this->getMockBuilder(ComponentContainerTrait::class)
            ->setMethods(['initialize'])
            ->getMockForTrait();
        $trait->expects($this->once())
            ->method('initialize');

        return $trait;
    }

    public function testGetAllComponents_InvokeToInitialize()
    {
        $trait = $this->getMock1();

        $trait->getAllComponents();
    }

    public function getMock2()
    {
        $trait = $this->getMockBuilder(ComponentContainerTrait::class)
            ->setMethods(['isInitialized', 'initialize'])
            ->getMockForTrait();
        $trait->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);
        $trait->expects($this->exactly(0))
            ->method('initialize');

        return $trait;
    }

    public function testGetAllComponents_NotInvokeToInitializeWhenAlreadyIsInitialized()
    {
        $trait = $this->getMock2();

        $trait->getAllComponents();
    }
}
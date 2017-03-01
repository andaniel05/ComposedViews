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
        $components = $this->trait->getAllComponents();

        $this->assertInstanceOf(ComponentCollection::class, $components);
        $this->assertEmpty($components);
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

    public function testAddComponent_InvokeToInitialize()
    {
        $trait = $this->getMock1();

        $dummy = $this->createMock(AbstractComponent::class);

        $trait->addComponent('dummy', $dummy);
    }

    public function testAddComponent_NotInvokeToInitializeWhenAlreadyIsInitialized()
    {
        $trait = $this->getMock2();

        $dummy = $this->createMock(AbstractComponent::class);

        $trait->addComponent('dummy', $dummy);
    }

    public function testGetComponent_ReturnNullWhenComponentNotExists()
    {
        $this->assertNull($this->trait->getComponent('id'));
    }

    public function insertComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component1->method('getId')->willReturn('component1');

        $this->component2 = $this->createMock(AbstractComponent::class);
        $this->component2->method('getId')->willReturn('component2');

        $this->trait->addComponent('component1', $this->component1);
        $this->trait->addComponent('component2', $this->component2);
    }

    public function testGetComponent_ReturnTheComponentWhenExists()
    {
        $this->insertComponents();

        $this->assertSame(
            $this->component1, $this->trait->getComponent('component1')
        );
    }

    public function testGetAllComponents_ReturnAnCollectionWithAllInsertedComponents()
    {
        $this->insertComponents();

        $components = $this->trait->getAllComponents();

        $this->assertInstanceOf(ComponentCollection::class, $components);
        $this->assertSame($this->component1, $components['component1']);
        $this->assertSame($this->component2, $components['component2']);
    }

    public function testGetAllComponents_ReturnCloningOfComponentsAttribute()
    {
        $components = $this->trait->getAllComponents();

        $this->assertAttributeNotSame($components, 'components', $this->trait);
    }

    public function testDeleteComponent_DeleteTheComponentIfExists()
    {
        $this->insertComponents();

        $this->trait->deleteComponent('component1');

        $this->assertNull($this->trait->getComponent('component1'));
    }

    public function testDeleteComponent_DoNotNothingIfComponentNotExists()
    {
        $this->insertComponents();

        $this->trait->deleteComponent('component100');

        $components = $this->trait->getAllComponents();

        $this->assertInstanceOf(ComponentCollection::class, $components);
        $this->assertSame($this->component1, $components['component1']);
        $this->assertSame($this->component2, $components['component2']);
    }

    public function testDeleteComponent_InvokeToInitialize()
    {
        $trait = $this->getMock1();

        $trait->deleteComponent('component100');
    }

    public function testDeleteComponent_NotInvokeToInitializeWhenAlreadyIsInitialized()
    {
        $trait = $this->getMock2();

        $trait->getAllComponents();
    }

    public function testGetComponent_InvokeToInitialize()
    {
        $trait = $this->getMock1();

        $trait->deleteComponent('component100');
    }

    public function testGetComponent_NotInvokeToInitializeWhenAlreadyIsInitialized()
    {
        $trait = $this->getMock2();

        $trait->getComponent('component100');
    }
}
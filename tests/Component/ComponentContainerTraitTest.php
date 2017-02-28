<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    ComponentContainerTrait};

class ComponentContainerTraitTest extends TestCase
{
    public function setUp()
    {
        $this->trait = $this->getMockForTrait(ComponentContainerTrait::class);
    }

    public function testGetAllComponents_ReturnNullByDefault()
    {
        $this->assertNull($this->trait->getAllComponents());
    }

    public function testGetComponent_ReturnNullIfComponentNotExists()
    {
        $this->assertNull($this->trait->getComponent('component1'));
    }

    public function insertTwoComponents()
    {
        $this->component1 = $this->createMock(AbstractComponent::class);
        $this->component2 = $this->createMock(AbstractComponent::class);

        $this->trait->insertComponent('component1', $this->component1);
        $this->trait->insertComponent('component2', $this->component2);
    }

    public function testGetAllComponents_ReturnACollectionWithAllInsertedComponents()
    {
        $this->insertTwoComponents();

        $components = $this->trait->getAllComponents();

        $this->assertSame($this->component1, $components['component1']);
        $this->assertSame($this->component2, $components['component2']);
    }

    // public function testGetComponent_ReturnTheComponentWhenExists()
    // {
    //     $this->insertTwoComponents();

    //     $this->assertSame(
    //         $this->component1,
    //         $this->trait->getComponent('component1')
    //     );
    // }
}
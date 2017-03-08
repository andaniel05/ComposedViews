<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    AbstractComposedComponent};
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\PrintTraitTests;
use PlatformPHP\ComposedViews\Tests\Asset\AssetsTraitTests;

class AbstractComponentTest extends TestCase
{
    use PrintTraitTests, AssetsTraitTests;

    public function setUp()
    {
        $this->component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component'])
            ->getMockForAbstractClass();
    }

    public function getTestClass() : string
    {
        return AbstractComponent::class;
    }

    public function provider1()
    {
        return [ ['component1'], ['component2'] ];
    }

    /**
     * @dataProvider provider1
     */
    public function testArgumentGetters($id)
    {
        $component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$id])
            ->getMockForAbstractClass();

        $this->assertEquals($id, $component->getId());
    }

    public function testGetParentReturnNullByDefault()
    {
        $this->assertNull($this->component->getParent());
    }

    public function setParentInComponent()
    {
        $this->parent = $this->getMockBuilder(AbstractComposedComponent::class)
            ->setConstructorArgs(['parent'])
            ->getMockForAbstractClass();

        $this->component->setParent($this->parent);
    }

    public function testGetParentReturnTheInsertedComponentBySetParent()
    {
        $this->setParentInComponent();

        $this->assertSame($this->parent, $this->component->getParent());
    }
}
<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\AbstractPage;
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Asset\AssetsTraitTests;

class AbstractComponentTest extends TestCase
{
    use AssetsTraitTests;

    public function setUp()
    {
        $this->component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component'])
            ->getMockForAbstractClass();
    }

    public function getTestClass(): string
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
        $this->parent = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['parent'])
            ->getMockForAbstractClass();

        $this->component->setParent($this->parent);
    }

    public function testGetParentReturnTheInsertedComponentBySetParent()
    {
        $this->setParentInComponent();

        $this->assertSame($this->parent, $this->component->getParent());
    }

    public function provider2()
    {
        return [
            ['child1', 'child2']
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testDetachInvokeDropComponentInTheParent($childId)
    {
        // Arrange
        //

        $parent = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['parent'])
            ->setMethods(['dropComponent'])
            ->getMockForAbstractClass();
        $parent->expects($this->once())
            ->method('dropComponent')
            ->with($this->equalTo($childId));

        $child = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs([$childId])
            ->getMockForAbstractClass();

        $parent->addComponent($child);

        // Act
        $child->detach();
    }

    public function testDetachDoNotNothingWhenParentIsNull()
    {
        $this->component->detach();

        $this->assertTrue(true);
    }

    public function testGetPage_ReturnNullByDefault()
    {
        $this->assertNull($this->component->getPage());
    }

    public function testChildrenHtml()
    {
        $component1Html = uniqid();
        $component1 = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['component1'])
            ->setMethods(['html'])
            ->getMockForAbstractClass();
        $component1->method('html')->willReturn($component1Html);

        $component2 = $this->getMockForAbstractClass(
            AbstractComponent::class, ['component2']
        );

        $component2->addComponent($component1);

            $expected = <<<HTML
<div class="cv-component cv-component1" id="cv-component1">
    $component1Html
</div>
HTML;

        $this->assertXmlStringEqualsXmlString($expected, $component2->childrenHtml());
    }
}
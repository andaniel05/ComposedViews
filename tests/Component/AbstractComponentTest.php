<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\AssetCollection;
use PlatformPHP\ComposedViews\Component\{AbstractComponent,
    ComponentContainerInterface};

class AbstractComponentTest extends TestCase
{
    public function setUp()
    {
        $this->component = $this->getMockBuilder(AbstractComponent::class)
            ->setConstructorArgs(['id'])
            ->getMockForAbstractClass();
    }

    public function provider1()
    {
        return [
            ['component1'],
            ['component2'],
        ];
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

    public function testGetParent_ReturnNullByDefault()
    {
        $this->assertNull($this->component->getParent());
    }

    public function testGetParent_ReturnInsertedValueBySetParent()
    {
        $parent = $this->createMock(ComponentContainerInterface::class);

        $child = $this->getMockBuilder(AbstractComponent::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $child->setParent($parent);

        $this->assertSame($parent, $child->getParent());
    }

    public function provider2()
    {
        return [
            ['result1'], ['result2'],
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testPrint_PrintResultOfRenderMethod($renderResult)
    {
        $component = $this->getMockBuilder(AbstractComponent::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMockForAbstractClass();
        $component->expects($this->once())
            ->method('render')
            ->willReturn($renderResult);

        $component->print();

        $this->expectOutputString($renderResult);
    }

    public function testGetAssets_AnEmptyAssetCollectionByDefault()
    {
        $assets = $this->component->getAssets();

        $this->assertInstanceOf(AssetCollection::class, $assets);
        $this->assertEmpty($assets);
    }
}
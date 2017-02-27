<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Component\AbstractComponent;

class AbstractComponentTest extends \PHPUnit_Framework_TestCase
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

    public function testGetParent_ReturnNullInDefault()
    {
        $this->assertNull($this->component->getParent());
    }

    public function testGetParent_ReturnInsertedValueBySetParent()
    {
        $parent = $this->getMockBuilder(AbstractComponent::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $child = clone $parent;
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

        ob_start();
        $component->print();
        $result = ob_get_clean();

        $this->assertEquals($renderResult, $result);
    }
}
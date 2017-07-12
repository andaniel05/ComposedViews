<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\PrintTraitTests;
use PlatformPHP\ComposedViews\Tests\Component\ComponentContainerTraitTests;

class SidebarTest extends TestCase
{
    use PrintTraitTests, ComponentContainerTraitTests;

    public function getTestClass(): string
    {
        return Sidebar::class;
    }

    public function getComponentContainerMock()
    {
        $container = $this->getMockBuilder($this->getTestClass())
            ->disableOriginalConstructor()
            ->setMethods();
        $container = $this->assumeMock($this->getTestClass(), $container);

        return $container;
    }

    public function provider1()
    {
        return [ ['sidebar1'], ['sidebar2'] ];
    }

    /**
     * @dataProvider provider1
     */
    public function testGetIdReturnIdArgument($sidebarId)
    {
        $sidebar = new Sidebar($sidebarId);

        $this->assertEquals($sidebarId, $sidebar->getId());
    }

    public function provider2()
    {
        return [
            ['result1', 'result2', 'result1result2'],
            ['result3', 'result4', 'result3result4'],
        ];
    }

    /**
     * @dataProvider provider2
     */
    public function testHtmlReturnAnStringWithHtmlResultOfAllComponents($result1, $result2, $expected)
    {
        $component1 = $this->createMock(AbstractComponent::class);
        $component1->method('html')->willReturn($result1);

        $component2 = $this->createMock(AbstractComponent::class);
        $component2->method('html')->willReturn($result2);

        $components = [
            'component1' => $component1,
            'component2' => $component2,
        ];

        $sidebar = $this->getMockBuilder(Sidebar::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllComponents'])
            ->getMock();
        $sidebar->expects($this->once())
            ->method('getAllComponents')
            ->willReturn($components);

        $this->assertEquals($expected, $sidebar->html());
    }
}
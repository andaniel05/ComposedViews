<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Component\ComponentContainerTraitTests;

class SidebarTest extends TestCase
{
    use ComponentContainerTraitTests;

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

    public function testHtml_ReturnTheChildrenHtmlResult()
    {
        $html = uniqid();
        $sidebar = $this->getMockBuilder(Sidebar::class)
            ->setConstructorArgs(['sidebar'])
            ->setMethods(['childrenHtml'])
            ->getMock();
        $sidebar->method('childrenHtml')->willReturn($html);

        $this->assertEquals($html, $sidebar->html());
    }
}
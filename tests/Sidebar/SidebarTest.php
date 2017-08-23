<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PHPUnit\Framework\TestCase;

class SidebarTest extends TestCase
{
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
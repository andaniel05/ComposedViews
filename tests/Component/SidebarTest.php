<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Component\{AbstractComponent, Sidebar};
use PHPUnit\Framework\TestCase;

class SidebarTest extends TestCase
{
    public function testGetIdReturnIdArgument()
    {
        $sidebarId = uniqid();

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
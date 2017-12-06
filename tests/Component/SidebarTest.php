<?php

namespace Andaniel05\ComposedViews\Tests\Component;

use Andaniel05\ComposedViews\Component\AbstractComponent;
use Andaniel05\ComposedViews\Component\Sidebar;
use PHPUnit\Framework\TestCase;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class SidebarTest extends TestCase
{
    public function testGetIdReturnIdArgument()
    {
        $sidebarId = uniqid();

        $sidebar = new Sidebar($sidebarId);

        $this->assertEquals($sidebarId, $sidebar->getId());
    }

    public function testHtml_ReturnTheRenderizeChildrenResult()
    {
        $html = uniqid();
        $sidebar = $this->getMockBuilder(Sidebar::class)
            ->setConstructorArgs(['sidebar'])
            ->setMethods(['renderizeChildren'])
            ->getMock();
        $sidebar->method('renderizeChildren')->willReturn($html);

        $this->assertEquals($html, $sidebar->html());
    }
}

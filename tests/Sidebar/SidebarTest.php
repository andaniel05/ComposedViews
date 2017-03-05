<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Sidebar\Sidebar;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\PrintTraitTests;

class SidebarTest extends TestCase
{
    use PrintTraitTests;

    public function getTestClass() : string
    {
        return Sidebar::class;
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
}
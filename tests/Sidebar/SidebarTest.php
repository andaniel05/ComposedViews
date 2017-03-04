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
}
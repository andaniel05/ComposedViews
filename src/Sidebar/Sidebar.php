<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

class Sidebar implements RenderInterface
{
    use PrintTrait;

    public function render() : string
    {
        return '';
    }
}
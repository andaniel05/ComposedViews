<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\Component\AbstractComponent;

class Sidebar extends AbstractComponent
{
    public function html(): string
    {
        $result = '';

        foreach ($this->getAllComponents() as $component) {
            $result .= $component->html();
        }

        return $result;
    }
}
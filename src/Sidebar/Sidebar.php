<?php

namespace PlatformPHP\ComposedViews\Sidebar;

use PlatformPHP\ComposedViews\Component\AbstractComponent;

class Sidebar extends AbstractComponent
{
    public function html(): ?string
    {
        return $this->childrenHtml();
    }
}
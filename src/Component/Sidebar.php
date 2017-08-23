<?php

namespace PlatformPHP\ComposedViews\Component;

class Sidebar extends AbstractComponent
{
    public function html(): ?string
    {
        return $this->childrenHtml();
    }
}
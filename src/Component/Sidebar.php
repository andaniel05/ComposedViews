<?php

namespace Andaniel05\ComposedViews\Component;

class Sidebar extends AbstractComponent
{
    public function html(): ?string
    {
        return $this->renderizeChildren();
    }
}
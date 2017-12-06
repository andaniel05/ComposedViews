<?php
declare(strict_types=1);

namespace Andaniel05\ComposedViews\Component;

class Sidebar extends AbstractComponent implements SidebarInterface
{
    public function html(): ?string
    {
        return $this->renderizeChildren();
    }
}

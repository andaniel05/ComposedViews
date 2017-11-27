<?php

namespace Andaniel05\ComposedViews\Tests\Builder;

use Andaniel05\ComposedViews\AbstractPage;

class Page extends AbstractPage
{
    public function sidebars(): array
    {
        return ['sidebar1', 'sidebar2'];
    }

    public function html(): ?string
    {
        return '';
    }
}

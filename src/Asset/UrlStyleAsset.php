<?php

namespace Andaniel05\ComposedViews\Asset;

class UrlStyleAsset extends AbstractUrlAsset
{
    public function __construct(string $id, string $url, ?string $minimizedUrl = null, array $dependencies = [], array $groups = [])
    {
        parent::__construct($id, $url, $minimizedUrl, $dependencies, $groups);

        $this->addGroup('url');
        $this->addGroup('styles');
    }

    public function html(): string
    {
        $href = $this->minimized ? $this->getMinimizedUrl() : $this->getUrl();
        return "<link href=\"$href\" />";
    }
}
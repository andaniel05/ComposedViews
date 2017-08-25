<?php

namespace PlatformPHP\ComposedViews\Asset2;

class UrlScriptAsset extends AbstractUrlAsset
{
    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $url = null, ?string $minimizedUrl = null)
    {
        parent::__construct($id, $groups, $dependencies, $url, $minimizedUrl);

        $this->addGroup('url');
        $this->addGroup('scripts');
    }

    public function html(): string
    {
        $href = $this->minimized ? $this->getMinimizedUrl() : $this->getUrl();
        return "<script src=\"$href\">";
    }
}
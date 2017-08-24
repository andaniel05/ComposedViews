<?php

namespace PlatformPHP\ComposedViews\Asset2;

class UrlAsset extends Asset2
{
    protected $url;
    protected $minimizedUrl;

    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $url = null, ?string $minimizedUrl = null)
    {
        parent::__construct($id, $groups, $dependencies);

        $this->url = $url;
        $this->minimizedUrl = $minimizedUrl;

        $this->addGroup('url');
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url)
    {
        $this->url = $url;
    }

    public function getMinimizedUrl(): ?string
    {
        return $this->minimizedUrl ?? $this->url;
    }

    public function setMinimizedUrl(?string $url)
    {
        $this->minimizedUrl = $url;
    }
}
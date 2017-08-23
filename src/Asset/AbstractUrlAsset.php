<?php

namespace PlatformPHP\ComposedViews\Asset;

abstract class AbstractUrlAsset extends AbstractAsset
{
    protected $url;
    protected $minimizedUrl;

    public function __construct(string $id)
    {
        parent::__construct($id);

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
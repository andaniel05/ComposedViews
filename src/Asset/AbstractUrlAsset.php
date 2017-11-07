<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElementInterface;

abstract class AbstractUrlAsset extends AbstractMinimizedAsset implements UrlAssetInterface
{
    protected $url;
    protected $minimizedUrl;

    public function __construct(string $id, string $url, ?string $minimizedUrl = null, array $dependencies = [], array $groups = [], HtmlElementInterface $element = null)
    {
        $this->url = $url;
        $this->minimizedUrl = $minimizedUrl;

        parent::__construct($id, $dependencies, $groups, $element);

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

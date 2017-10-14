<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElementInterface;

interface UrlAssetInterface extends MinimizedAssetInterface
{
    public function getUrl(): ?string;

    public function setUrl(?string $url);

    public function getMinimizedUrl(): ?string;

    public function setMinimizedUrl(?string $url);
}
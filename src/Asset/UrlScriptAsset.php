<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class UrlScriptAsset extends AbstractUrlAsset
{
    public function __construct(string $id, string $url, ?string $minimizedUrl = null, array $dependencies = [], array $groups = [])
    {
        $element = new HtmlElement('script');
        $element->setEndTag(null);

        parent::__construct($id, $url, $minimizedUrl, $dependencies, $groups, $element);

        $this->addGroup('url');
        $this->addGroup('scripts');
    }

    public function updateHtmlElement()
    {
        $src = $this->minimized ? $this->getMinimizedUrl() : $this->getUrl();
        $this->element->setAttribute('src', $src);
    }
}

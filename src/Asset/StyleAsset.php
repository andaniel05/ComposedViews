<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class StyleAsset extends AbstractUrlAsset
{
    public function __construct(string $id, string $url, ?string $minimizedUrl = null, array $dependencies = [], array $groups = [])
    {
        $element = new HtmlElement('link');
        $element->setEndTag(null);

        parent::__construct($id, $url, $minimizedUrl, $dependencies, $groups, $element);

        $this->addGroup('url');
        $this->addGroup('styles');
    }

    public function updateHtmlElement()
    {
        $href = $this->minimized ? $this->getMinimizedUrl() : $this->getUrl();
        $this->element->setAttribute('href', $href);
    }
}

<?php

namespace Andaniel05\ComposedViews\Asset;

use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class ImportAsset extends AbstractUrlAsset
{
    public function __construct(string $id, string $url, array $dependencies = [], array $groups = [])
    {
        $element = new HtmlElement('link');
        $element->setAttribute('rel', 'import');
        $element->setEndTag(false);

        parent::__construct($id, $url, null, $dependencies, $groups, $element);

        $this->addGroup('imports');
    }

    public function updateHtmlElement()
    {
        $this->element->setAttribute('href', $this->url);
    }
}

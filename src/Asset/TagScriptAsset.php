<?php

namespace Andaniel05\ComposedViews\Asset;

use MatthiasMullie\Minify\JS as JSMinimizer;
use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class TagScriptAsset extends AbstractMinimizedAsset
{
    protected $minimizer;

    public function __construct(string $id, string $content, array $dependencies = [], array $groups = [])
    {
        $element = new HtmlElement('script');

        parent::__construct($id, $dependencies, $groups, $element);

        $this->content = $content;
        $this->addGroup('tag');
        $this->addGroup('scripts');
    }

    public function getMinimizer(): ?JSMinimizer
    {
        return $this->minimizer;
    }

    public function setMinimizer(?JSMinimizer $minimizer)
    {
        $this->minimizer = $minimizer;
    }

    public function getMinimizedContent(): ?string
    {
        if ( ! $this->minimizedContent) {
            $this->minimizer = $this->minimizer ?? new JSMinimizer($this->content);
            return $this->minimizer->minify();
        } else {
            return $this->minimizedContent;
        }
    }

    public function updateHtmlElement()
    {
        if ($this->minimized) {
            $this->element->setContent([$this->getMinimizedContent()]);
        } else {
            $this->element->setContent(["\n", $this->getContent(), "\n"]);
        }
    }
}
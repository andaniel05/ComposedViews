<?php

namespace Andaniel05\ComposedViews\Asset;

use MatthiasMullie\Minify\CSS as CSSMinimizer;
use Andaniel05\ComposedViews\HtmlElement\HtmlElement;

class ContentStyleAsset extends AbstractMinimizedAsset
{
    use UpdateHtmlElementTagAssetTrait;

    protected $minimizer;

    public function __construct(string $id, string $content, array $dependencies = [], array $groups = [])
    {
        $element = new HtmlElement('style');

        parent::__construct($id, $dependencies, $groups, $element);

        $this->content = $content;
        $this->addGroup('content');
        $this->addGroup('styles');
    }

    public function getMinimizer(): ?CSSMinimizer
    {
        return $this->minimizer;
    }

    public function setMinimizer(?CSSMinimizer $minimizer)
    {
        $this->minimizer = $minimizer;
    }

    public function getMinimizedContent(): ?string
    {
        if ( ! $this->minimizedContent) {
            $this->minimizer = $this->minimizer ?? new CSSMinimizer($this->content);
            return $this->minimizer->minify();
        } else {
            return $this->minimizedContent;
        }
    }
}

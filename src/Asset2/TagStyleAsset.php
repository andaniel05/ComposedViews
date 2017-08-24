<?php

namespace PlatformPHP\ComposedViews\Asset2;

use MatthiasMullie\Minify\CSS as CSSMinimizer;

class TagStyleAsset extends TagAsset
{
    protected $minimizer;

    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $content = null, ?string $minimizedContent = null)
    {
        parent::__construct($id, $groups, $dependencies, $content, $minimizedContent);

        $this->addGroup('styles');

        $this->minimizer = new CSSMinimizer();
    }

    public function getMinimizer(): CSSMinimizer
    {
        return $this->minimizer;
    }

    public function setMinimizer(CSSMinimizer $minimizer)
    {
        $this->minimizer = $minimizer;
    }

    public function getMinimizedContent(): ?string
    {
        if ( ! $this->minimizedContent) {
            $this->minimizer->add($this->content);
            return $this->minimizer->minify();
        } else {
            return $this->minimizedContent;
        }
    }
}
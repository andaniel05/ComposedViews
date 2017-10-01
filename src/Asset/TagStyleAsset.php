<?php

namespace Andaniel05\ComposedViews\Asset;

use MatthiasMullie\Minify\CSS as CSSMinimizer;

class TagStyleAsset extends AbstractMinimizedAsset
{
    protected $minimizer;

    public function __construct(string $id, string $content, array $dependencies = [], array $groups = [])
    {
        parent::__construct($id, $dependencies, $groups);

        $this->content = $content;
        $this->addGroup('tag');
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

    public function html(): string
    {
        if ($this->minimized) {
            return "<style>{$this->getMinimizedContent()}</style>";
        } else {
            return "<style>\n{$this->getContent()}\n</style>";
        }
    }
}
<?php

namespace Andaniel05\ComposedViews\Asset;

use MatthiasMullie\Minify\JS as JSMinimizer;

class TagScriptAsset extends AbstractMinimizedAsset
{
    protected $minimizer;

    public function __construct(string $id, string $content, array $dependencies = [], array $groups = [])
    {
        parent::__construct($id, $dependencies, $groups);

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

    public function html(): string
    {
        if ($this->minimized) {
            return "<script>{$this->getMinimizedContent()}</script>";
        } else {
            return "<script>\n{$this->getContent()}\n</script>";
        }
    }
}
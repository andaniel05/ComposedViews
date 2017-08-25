<?php

namespace PlatformPHP\ComposedViews\Asset2;

use MatthiasMullie\Minify\JS as JSMinimizer;

class TagScriptAsset extends AbstractMinimizedAsset
{
    protected $minimizer;

    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $content = null, ?string $minimizedContent = null)
    {
        parent::__construct($id, $groups, $dependencies, $content, $minimizedContent);

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
        $source = $this->minimized ?
            $this->getMinimizedContent() :
            $this->getContent();

        return "<script>$source</script>";
    }
}
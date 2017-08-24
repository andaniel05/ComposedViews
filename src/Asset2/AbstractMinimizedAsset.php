<?php

namespace PlatformPHP\ComposedViews\Asset2;

abstract class AbstractMinimizedAsset extends AbstractAsset
{
    protected $minimizedContent;

    public function __construct(string $id, array $groups = [], array $dependencies = [], ?string $content = null, ?string $minimizedContent = null)
    {
        parent::__construct($id, $groups, $dependencies, $content);

        $this->minimizedContent = $minimizedContent;
    }

    public function getMinimizedContent(): ?string
    {
        return $this->minimizedContent ?? $this->content;
    }

    public function setMinimizedContent(?string $minimized)
    {
        $this->minimizedContent = $minimized;
    }
}
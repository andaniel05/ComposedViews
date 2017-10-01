<?php

namespace Andaniel05\ComposedViews\Asset;

abstract class AbstractMinimizedAsset extends AbstractAsset
{
    protected $minimizedContent;
    protected $minimized = true;

    public function getMinimizedContent(): ?string
    {
        return $this->minimizedContent ?? $this->content;
    }

    public function setMinimizedContent(?string $minimized)
    {
        $this->minimizedContent = $minimized;
    }

    public function isMinimized(): bool
    {
        return $this->minimized;
    }

    public function setMinimized(bool $value)
    {
        $this->minimized = $value;
    }
}
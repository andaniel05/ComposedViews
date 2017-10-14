<?php

namespace Andaniel05\ComposedViews\Asset;

interface MinimizedAssetInterface extends AssetInterface
{
    public function getMinimizedContent(): ?string;

    public function setMinimizedContent(?string $minimized);

    public function isMinimized(): bool;

    public function setMinimized(bool $value);
}
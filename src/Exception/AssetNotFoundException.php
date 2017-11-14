<?php

namespace Andaniel05\ComposedViews\Exception;

class AssetNotFoundException extends ComposedViewsException
{
    public function __construct(string $assetId, string $dependency)
    {
        parent::__construct("Asset '$dependency' not found.");
    }
}

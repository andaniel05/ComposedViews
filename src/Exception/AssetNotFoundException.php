<?php

namespace Andaniel05\ComposedViews\Exception;

class AssetNotFoundException extends ComposedViewsException
{
    public function __construct(string $assetId)
    {
        parent::__construct("Asset '{$assetId}' not found.");
    }
}

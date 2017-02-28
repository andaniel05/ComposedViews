<?php

namespace PlatformPHP\ComposedViews\Asset;

use Andaniel05\ObjectCollection\ObjectCollection;

class AssetCollection extends ObjectCollection
{
    public function __construct()
    {
        parent::__construct(Asset::class);
    }
}
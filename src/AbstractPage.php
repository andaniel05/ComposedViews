<?php

namespace PlatformPHP\ComposedViews;

use PlatformPHP\ComposedViews\Asset\AssetCollection;

class AbstractPage
{
    public function getPageAssets() : AssetCollection
    {
        return new AssetCollection();
    }
}
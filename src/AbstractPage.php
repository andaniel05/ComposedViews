<?php

namespace PlatformPHP\ComposedViews;

class AbstractPage
{
    public function getPageAssets() : ?AssetCollection
    {
        return null;
    }
}
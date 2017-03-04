<?php

namespace PlatformPHP\ComposedViews\Tests\Asset;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Asset\AssetsTrait;

class AssetsTraitTest extends TestCase
{
    use AssetsTraitTests;

    public function getTestClass() : string
    {
        return AssetsTrait::class;
    }
}
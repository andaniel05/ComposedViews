<?php

namespace PlatformPHP\ComposedViews\Tests;

use PlatformPHP\ComposedViews\Component\AbstractComponent;
use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\PrintTraitTests;
use PlatformPHP\ComposedViews\Tests\Asset\AssetsTraitTests;

class AbstractComponentTest extends TestCase
{
    use PrintTraitTests, AssetsTraitTests;

    public function getTestClass() : string
    {
        return AbstractComponent::class;
    }
}
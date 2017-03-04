<?php

namespace PlatformPHP\ComposedViews\Component;

use PlatformPHP\ComposedViews\RenderInterface;
use PlatformPHP\ComposedViews\Asset\AssetsTrait;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

Abstract class AbstractComponent implements RenderInterface
{
    use AssetsTrait, PrintTrait;
}
<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Component\ComponentContainerTrait;

class ComponentContainerTraitTest extends TestCase
{
    use ComponentContainerTraitTests;

    public function getTestClass(): string
    {
        return ComponentContainerTrait::class;
    }
}
<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\CloningTraitTests;
use PlatformPHP\ComposedViews\Component\{ComponentContainerTrait,
    AbstractComposedComponent};

class AbstractComposedComponentTest extends TestCase
{
    use ComponentContainerTraitTests, CloningTraitTests;

    public function getTestClass(): string
    {
        return AbstractComposedComponent::class;
    }
}
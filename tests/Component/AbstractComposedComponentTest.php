<?php

namespace PlatformPHP\ComposedViews\Tests\Component;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Tests\Traits\CloningTraitTests;
use PlatformPHP\ComposedViews\Component\{ComponentContainerTrait,
    AbstractSuperComponent};

class AbstractSuperComponentTest extends TestCase
{
    use ComponentContainerTraitTests, CloningTraitTests;

    public function getTestClass(): string
    {
        return AbstractSuperComponent::class;
    }
}
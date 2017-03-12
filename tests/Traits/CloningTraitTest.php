<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Traits\CloningTrait;

class CloningTraitTest extends TestCase
{
    use CloningTraitTests;

    public function getTestClass()
    {
        return CloningTrait::class;
    }
}
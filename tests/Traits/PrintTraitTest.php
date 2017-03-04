<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Traits\PrintTrait;

class PrintTraitTest extends TestCase
{
    use PrintTraitTests;

    public function getTestClass()
    {
        return PrintTrait::class;
    }
}
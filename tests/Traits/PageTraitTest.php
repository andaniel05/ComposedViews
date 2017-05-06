<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PlatformPHP\ComposedViews\Tests\TestCase;
use PlatformPHP\ComposedViews\Traits\PageTrait;

class PageTraitTest extends TestCase
{
    use PageTraitTests;

    public function getTestClass()
    {
        return PageTrait::class;
    }
}
<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\AbstractPage;

class AbstractPageTest extends TestCase
{
    public function setUp()
    {
        $this->page = $this->getMockForAbstractClass(AbstractPage::class);
    }

    public function testPageAssets_ReturnNullByDefault()
    {
        $this->assertNull($this->page->getPageAssets());
    }
}
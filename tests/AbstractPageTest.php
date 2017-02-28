<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\AssetCollection;
use PlatformPHP\ComposedViews\AbstractPage;

class AbstractPageTest extends TestCase
{
    public function setUp()
    {
        $this->page = $this->getMockForAbstractClass(AbstractPage::class);
    }

    public function testPageAssets_AnEmptyAssetCollectionByDefault()
    {
        $assets = $this->page->getPageAssets();

        $this->assertInstanceOf(AssetCollection::class, $assets);
        $this->assertEmpty($assets);
    }
}
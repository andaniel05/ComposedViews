<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\AbstractUrlAsset;

class AbstractUrlAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = $this->getMockForAbstractClass(AbstractUrlAsset::class, [$id]);
    }

    public function testHasUrlGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('url'));
    }

    public function testGetUrl_ReturnNullByDefault()
    {
        $this->assertNull($this->asset->getUrl());
    }

    public function testGetUrl_ReturnInsertedValueBySetUrl()
    {
        $url = uniqid();
        $this->asset->setUrl($url);

        $this->assertEquals($url, $this->asset->getUrl());
    }
}
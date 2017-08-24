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

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $url = uniqid();
        $minimizedUrl = uniqid();

        $asset = $this->getMockForAbstractClass(
            AbstractUrlAsset::class,
            [$id, $groups, $deps, $url, $minimizedUrl]
        );

        $this->assertEquals($id, $asset->getId());
        $this->assertArraySubset($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($url, $asset->getUrl());
        $this->assertEquals($minimizedUrl, $asset->getMinimizedUrl());
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

    public function testGetMinimizedUrl_ReturnValueOfGetUrlByDefault()
    {
        $url = uniqid();
        $this->asset->setUrl($url);

        $this->assertEquals($url, $this->asset->getMinimizedUrl());
    }

    public function testGetMinimizedUrl_ReturnInsertedValueBySetMinimizedUrl()
    {
        $url = uniqid();
        $this->asset->setMinimizedUrl($url);

        $this->assertEquals($url, $this->asset->getMinimizedUrl());
    }
}
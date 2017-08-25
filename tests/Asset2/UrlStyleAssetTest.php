<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\UrlStyleAsset;

class UrlStyleAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new UrlStyleAsset($id);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $url = uniqid();
        $minimizedUrl = uniqid();

        $asset = new UrlStyleAsset($id, $groups, $deps, $url, $minimizedUrl);

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

    public function testHasStylesGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('styles'));
    }

    public function testHtml_RenderizeTheMinimizedUrlByDefault()
    {
        $minimizedUrl = uniqid();
        $asset = new UrlStyleAsset('asset', [], [], null, $minimizedUrl);

        $this->assertEquals(
            "<link href=\"$minimizedUrl\" />", $asset->html()
        );
    }

    public function testHtml_RenderizeTheUrlWhenAssetHasNotMinimizedStatus()
    {
        $url = uniqid();
        $minimizedUrl = uniqid();
        $asset = new UrlStyleAsset('asset', [], [], $url, $minimizedUrl);
        $asset->setMinimized(false);

        $this->assertEquals(
            "<link href=\"$url\" />", $asset->html()
        );
    }
}
<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\UrlScriptAsset;

class UrlScriptAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new UrlScriptAsset($id, '');
    }

    public function testConstructor()
    {
        $id = uniqid();
        $url = uniqid();
        $minimizedUrl = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = new UrlScriptAsset($id, $url, $minimizedUrl, $deps, $groups);

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($url, $asset->getUrl());
        $this->assertEquals($minimizedUrl, $asset->getMinimizedUrl());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertArraySubset($groups, $asset->getGroups());
    }

    public function testHasUrlGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('url'));
    }

    public function testHasScriptsGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('scripts'));
    }

    public function testHtml_RenderizeTheMinimizedUrlByDefault()
    {
        $minimizedUrl = uniqid();
        $asset = new UrlScriptAsset('asset', '', $minimizedUrl);

        $this->assertEquals(
            "<script src=\"$minimizedUrl\">", $asset->html()
        );
    }

    public function testHtml_RenderizeTheUrlWhenAssetHasNotMinimizedStatus()
    {
        $url = uniqid();
        $minimizedUrl = uniqid();
        $asset = new UrlScriptAsset('asset', $url, $minimizedUrl);
        $asset->setMinimized(false);

        $this->assertEquals(
            "<script src=\"$url\">", $asset->html()
        );
    }
}
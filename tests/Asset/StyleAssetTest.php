<?php

namespace Andaniel05\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\StyleAsset;

class StyleAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new StyleAsset($id, '');
    }

    public function testConstructor()
    {
        $id = uniqid();
        $url = uniqid();
        $minimizedUrl = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = new StyleAsset($id, $url, $minimizedUrl, $deps, $groups);

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

    public function testHasStylesGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('styles'));
    }

    public function testHtml_RenderizeTheMinimizedUrlByDefault()
    {
        $minimizedUrl = uniqid();
        $asset = new StyleAsset('asset', '', $minimizedUrl);

        $this->assertEquals(
            "<link href=\"$minimizedUrl\" />", $asset->html()
        );
    }

    public function testHtml_RenderizeTheUrlWhenAssetHasNotMinimizedStatus()
    {
        $url = uniqid();
        $minimizedUrl = uniqid();
        $asset = new StyleAsset('asset', $url, $minimizedUrl);
        $asset->setMinimized(false);

        $this->assertEquals(
            "<link href=\"$url\" />", $asset->html()
        );
    }

    public function testTheHtmlElementTagIsLink()
    {
        $this->assertEquals('link', $this->asset->getHtmlElement()->getTag());
    }

    public function testTheHtmlElementNotHasEndTag()
    {
        $this->assertNull($this->asset->getHtmlElement()->getEndTag());
    }
}

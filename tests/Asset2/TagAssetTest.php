<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\TagAsset;

class TagAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new TagAsset($id);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $content = uniqid();
        $minimized = uniqid();

        $asset = new TagAsset($id, $groups, $deps, $content, $minimized);

        $this->assertEquals($id, $asset->getId());
        $this->assertArraySubset($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($content, $asset->getContent());
        $this->assertEquals($minimized, $asset->getMinimizedContent());
    }

    public function testHasTagGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('tag'));
    }
}
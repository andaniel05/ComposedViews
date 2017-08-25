<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\AbstractUrlAsset;

class AbstractUrlAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = $this->getMockForAbstractClass(AbstractUrlAsset::class, [$id, '']);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $url = uniqid();
        $minimizedUrl = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = $this->getMockForAbstractClass(
            AbstractUrlAsset::class,
            [$id, $url, $minimizedUrl, $deps, $groups]
        );

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

    public function testGetUrl_ReturnAnEmptyStringByDefault()
    {
        $this->assertEmpty($this->asset->getUrl());
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
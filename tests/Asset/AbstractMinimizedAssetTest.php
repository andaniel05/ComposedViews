<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\AbstractMinimizedAsset;

class AbstractMinimizedAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = $this->getMockForAbstractClass(AbstractMinimizedAsset::class, [$id]);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $content = uniqid();
        $minimized = uniqid();

        $asset = $this->getMockForAbstractClass(
            AbstractMinimizedAsset::class,
            [$id, $groups, $deps, $content, $minimized]
        );

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($content, $asset->getContent());
        $this->assertEquals($minimized, $asset->getMinimizedContent());
    }

    public function testGetMinimizedContent_ReturnValueOfTheContentWhenHisMinimizedContentIsNull()
    {
        $content = uniqid();
        $this->asset->setContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
    }

    public function testGetMinimizedContent_ReturnInsertedValueBySetMinimizedContent()
    {
        $content = uniqid();
        $this->asset->setMinimizedContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
    }

    public function testIsMinimized_ReturnTrueByDefault()
    {
        $this->assertTrue($this->asset->isMinimized());
    }

    public function testIsMinimized_ReturnInsertedValueBySetMinimized()
    {
        $this->asset->setMinimized(false);

        $this->assertFalse($this->asset->isMinimized());
    }
}
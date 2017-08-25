<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\TagStyleAsset;
use MatthiasMullie\Minify\CSS as CSSMinimizer;

class TagStyleAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new TagStyleAsset($id, '');
    }

    public function testConstructor()
    {
        $id = uniqid();
        $content = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = new TagStyleAsset($id, $content, $deps, $groups);

        $this->assertEquals($id, $asset->getId());
        $this->assertArraySubset($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($content, $asset->getContent());
    }

    public function testHasTagGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('tag'));
    }

    public function testHasStylesGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('styles'));
    }

    public function testGetMinimizer_ReturnNullByDefault()
    {
        $this->assertNull($this->asset->getMinimizer());
    }

    public function testGetMinimizer_ReturnTheInsertedMinimizerBySetMinimizer()
    {
        $minimizer = new CSSMinimizer();
        $this->asset->setMinimizer($minimizer);

        $this->assertEquals($minimizer, $this->asset->getMinimizer());
    }

    public function testGetMinimizedContent_ReturnResultOfMinimizeTheContent()
    {
        $minimizedContent = uniqid();
        $minimizer = $this->getMockBuilder(CSSMinimizer::class)
            ->setMethods(['minify'])
            ->getMock();
        $minimizer->expects($this->once())
            ->method('minify')
            ->willReturn($minimizedContent);

        $this->asset->setMinimizer($minimizer);

        $this->assertEquals($minimizedContent, $this->asset->getMinimizedContent());
    }

    public function testGetMinimizedContent_ReturnTheSameResult()
    {
        $content = uniqid();
        $this->asset->setContent($content);

        $minimizedContent = $this->asset->getMinimizedContent();

        $this->assertEquals($minimizedContent, $this->asset->getMinimizedContent());
    }

    public function testHtml_RenderizeTheMinimizedContentByDefault()
    {
        $minimizedContent = uniqid();
        $asset = new TagStyleAsset('asset', '');
        $asset->setMinimizedContent($minimizedContent);

        $this->assertXmlStringEqualsXmlString(
            "<style>$minimizedContent</style>", $asset->html()
        );
    }

    public function testHtml_RenderizeTheContentWhenAssetHasNotMinimizedStatus()
    {
        $content = uniqid();
        $minimizedContent = uniqid();
        $asset = new TagStyleAsset('asset', $content);
        $asset->setMinimized(false);
        $asset->setMinimizedContent($minimizedContent);

        $this->assertXmlStringEqualsXmlString(
            "<style>\n$content\n</style>", $asset->html()
        );
    }
}
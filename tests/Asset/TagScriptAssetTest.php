<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\TagScriptAsset;
use MatthiasMullie\Minify\JS as JSMinimizer;

class TagScriptAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new TagScriptAsset($id, '');
    }

    public function testConstructor()
    {
        $id = uniqid();
        $content = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = new TagScriptAsset($id, $content, $deps, $groups);

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($content, $asset->getContent());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertArraySubset($groups, $asset->getGroups());
    }

    public function testHasTagGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('tag'));
    }

    public function testHasScriptsGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('scripts'));
    }

    public function testGetMinimizer_ReturnNullByDefault()
    {
        $this->assertNull($this->asset->getMinimizer());
    }

    public function testGetMinimizer_ReturnTheInsertedMinimizerBySetMinimizer()
    {
        $minimizer = new JSMinimizer();
        $this->asset->setMinimizer($minimizer);

        $this->assertEquals($minimizer, $this->asset->getMinimizer());
    }

    public function testGetMinimizedContent_ReturnResultOfMinimizeTheContent()
    {
        $minimizedContent = uniqid();
        $minimizer = $this->getMockBuilder(JSMinimizer::class)
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
        $asset = new TagScriptAsset('asset', '');
        $asset->setMinimizedContent($minimizedContent);

        $this->assertXmlStringEqualsXmlString(
            "<script>$minimizedContent</script>", $asset->html()
        );
    }

    public function testHtml_RenderizeTheContentWhenAssetHasNotMinimizedStatus()
    {
        $content = uniqid();
        $minimizedContent = uniqid();
        $asset = new TagScriptAsset('asset', $content);
        $asset->setMinimizedContent($minimizedContent);
        $asset->setMinimized(false);

        $this->assertXmlStringEqualsXmlString(
            "<script>\n$content\n</script>", $asset->html()
        );
    }
}
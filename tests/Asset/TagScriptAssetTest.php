<?php

namespace Andaniel05\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\TagScriptAsset;
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

        $this->assertEquals(
            "<script>{$minimizedContent}</script>", $asset->html()
        );
    }

    public function testHtml_RenderizeTheContentWhenAssetHasNotMinimizedStatus()
    {
        $content = uniqid();
        $minimizedContent = uniqid();
        $asset = new TagScriptAsset('asset', $content);
        $asset->setMinimizedContent($minimizedContent);
        $asset->setMinimized(false);

        $this->assertEquals(
            "<script>\n{$content}\n</script>", $asset->html()
        );
    }

    public function testTheHtmlElementTagIsScript()
    {
        $this->assertEquals('script', $this->asset->getHtmlElement()->getTag());
    }

    public function testTheContentOfHtmlElementIsEqualToResultOfGetMinimizedContentByDefault()
    {
        $content = uniqid();
        $asset = $this->getMockBuilder(TagScriptAsset::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMinimizedContent'])
            ->getMock();
        $asset->method('getMinimizedContent')->willReturn($content);
        $asset->__construct('id', $content);

        $this->assertEquals(
            $asset->getMinimizedContent(),
            $asset->getHtmlElement()->getContent()[0]
        );
    }

    public function testTheContentOfHtmlElementIsEqualToResultOfGetContentWhenAssetIsNotMinimized()
    {
        $content = uniqid();
        $asset = $this->getMockBuilder(TagScriptAsset::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContent'])
            ->getMock();
        $asset->method('getContent')->willReturn($content);
        $asset->__construct('id', '');

        $asset->setMinimized(false);
        $asset->updateHtmlElement(); // Act

        $this->assertEquals(
            $asset->getContent(),
            $asset->getHtmlElement()->getContent()[1]
        );
    }
}
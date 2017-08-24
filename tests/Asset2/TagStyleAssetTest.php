<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\TagStyleAsset;
use MatthiasMullie\Minify\CSS as CSSMinimizer;

class TagStyleAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new TagStyleAsset($id);
    }

    public function testConstructor()
    {
        $id = uniqid();
        $groups = range(0, rand(0, 10));
        $deps = range(0, rand(0, 10));
        $content = uniqid();
        $minimized = uniqid();

        $asset = new TagStyleAsset($id, $groups, $deps, $content, $minimized);

        $this->assertEquals($id, $asset->getId());
        $this->assertArraySubset($groups, $asset->getGroups());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertEquals($content, $asset->getContent());
        $this->assertEquals($minimized, $asset->getMinimizedContent());
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

    public function testHtml_RenderizeTheMinimizedContentWhenArgumentIsOmitted()
    {
        $minimizedContent = uniqid();

        $asset = new TagStyleAsset('asset', [], [], null, $minimizedContent);

        $expected = new \DOMDocument;
        $expected->loadXML("<style>$minimizedContent</style>");

        $actual = new \DOMDocument;
        $actual->loadXML($asset->html());

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild
        );
    }

    public function testHtml_RenderizeTheContentWhenArgumentIsFalse()
    {
        $content = uniqid();

        $asset = new TagStyleAsset('asset', [], [], $content);

        $expected = new \DOMDocument;
        $expected->loadXML("<style>$content</style>");

        $actual = new \DOMDocument;
        $actual->loadXML($asset->html(false));

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild
        );
    }
}
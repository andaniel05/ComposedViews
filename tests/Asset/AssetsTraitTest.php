<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\AssetsTrait;

class AssetsTraitTest extends TestCase
{
    public function setUp()
    {
        $this->trait = $this->getMockForTrait(AssetsTrait::class);
    }

    public function testGetAssets_ReturnAnEmptyArrayByDefault()
    {
        $this->assertEquals([], $this->trait->getAssets());
    }

    public function getMock(array $assets = [])
    {
        $trait = $this->getMockBuilder(AssetsTrait::class)
            ->setMethods(['assets'])
            ->getMockForTrait();
        $trait->expects($this->once())
            ->method('assets')->willReturn($assets);

        $trait->initializeAssets();

        return $trait;
    }

    public function testGetAssets_ReturnAnEmptyArray_WhenAssetsReturnAnEmptyArrayToo()
    {
        $trait = $this->getMock();

        $this->assertEquals([], $trait->getAssets());
    }

    public function testInitializeAssets()
    {
        $def = [
            'styles' => [
                ['bootstrap', '/css/bootstrap.css', [], ],
                ['custom', '/css/custom.css', ['bootstrap'], '* {color: black}'],
            ],
            'scripts' => [
                ['jquery', '/js/jquery.js'],
            ],
        ];

        $trait = $this->getMock($def);

        $assets = $trait->getAssets();
        $bootstrap = $assets['bootstrap'];
        $custom = $assets['custom'];
        $jquery = $assets['jquery'];

        $this->assertCount(3, $assets);

        $this->assertEquals('bootstrap', $bootstrap->getId());
        $this->assertEquals('styles', $bootstrap->getGroup());
        $this->assertEquals('/css/bootstrap.css', $bootstrap->getUrl());
        $this->assertEquals([], $bootstrap->getDependencies());
        $this->assertEquals(null, $bootstrap->getContent());

        $this->assertEquals('custom', $custom->getId());
        $this->assertEquals('styles', $custom->getGroup());
        $this->assertEquals('/css/custom.css', $custom->getUrl());
        $this->assertEquals(['bootstrap'], $custom->getDependencies());
        $this->assertEquals('* {color: black}', $custom->getContent());

        $this->assertEquals('jquery', $jquery->getId());
        $this->assertEquals('scripts', $jquery->getGroup());
        $this->assertEquals('/js/jquery.js', $jquery->getUrl());
        $this->assertEquals([], $jquery->getDependencies());
        $this->assertEquals(null, $jquery->getContent());
    }
}
<?php

namespace Andaniel05\ComposedViews\Tests\Asset2;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset2\{ScriptAsset, UriInterface};

class ScriptAssetTest extends TestCase
{
    public function setUp()
    {
        $this->asset = $this->getInstance();
    }

    public function getInstance(array $args = [])
    {
        $defaults = [
            'id'   => uniqid(),
            'uri'  => uniqid(),
            'deps' => uniqid(),
        ];

        $args = array_merge($defaults, $args);
        extract($args);

        return new ScriptAsset($id, $uri, $deps);
    }

    public function testGetIdReturnTheIdArgument()
    {
        $id = uniqid();
        $asset = $this->getInstance(['id' => $id]);

        $this->assertEquals($id, $asset->getId());
    }

    public function testSetDependenciesFromDepsArgument()
    {
        $dep1 = uniqid();

        $asset = $this->getInstance(['deps' => $dep1]);

        $this->assertTrue($asset->hasDependency($dep1));
    }

    public function testTagIsEqualToScript()
    {
        $this->assertEquals('script', $this->asset->getTag());
    }

    public function testHasScriptsGroup()
    {
        $this->assertTrue($this->asset->hasGroup('scripts'));
    }

    public function testHasUriGroup()
    {
        $this->assertTrue($this->asset->hasGroup('uri'));
    }

    public function testSrcAttributeIsEqualToUriArgument()
    {
        $uri = uniqid();
        $asset = $this->getInstance(['uri' => $uri]);

        $this->assertEquals($uri, $asset->getAttribute('src'));
    }

    public function testSetUriChangeTheSrcAttribute()
    {
        $uri = uniqid();
        $this->asset->setUri($uri);

        $this->assertEquals($uri, $this->asset->getAttribute('src'));
    }

    public function testGetUriReturnResultOfSrcAttribute()
    {
        $uri = uniqid();
        $this->asset->setAttribute('src', $uri);

        $this->assertEquals($uri, $this->asset->getUri());
    }

    public function testIsInstanceOfUriInterface()
    {
        $this->assertInstanceOf(UriInterface::class, $this->asset);
    }
}

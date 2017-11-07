<?php

namespace Andaniel05\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\ImportAsset;

class ImportAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = new ImportAsset($id, '');
    }

    public function testConstructor()
    {
        $id = uniqid();
        $url = uniqid();
        $deps = range(0, rand(0, 10));
        $groups = range(0, rand(0, 10));

        $asset = new ImportAsset($id, $url, $deps, $groups);

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($url, $asset->getUrl());
        $this->assertEquals($deps, $asset->getDependencies());
        $this->assertArraySubset($groups, $asset->getGroups());
    }

    public function testHasImportsGroupByDefault()
    {
        $this->assertTrue($this->asset->inGroup('imports'));
    }

    public function testTheHtmlElementTagIsLink()
    {
        $this->assertEquals('link', $this->asset->getHtmlElement()->getTag());
    }

    public function testTheHtmlElementNotHasEndTag()
    {
        $this->assertFalse($this->asset->getHtmlElement()->getEndTag());
    }

    public function testTheHtmlElementHasHrefAttributeWithValueEqualToUrlArgument()
    {
        $url = uniqid();
        $asset = new ImportAsset('id', $url);

        $this->assertEquals(
            $url, $asset->getHtmlElement()->getAttribute('href')
        );
    }
}

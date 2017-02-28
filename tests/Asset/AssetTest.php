<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset\Asset;

class AssetTest extends TestCase
{
    public function provider1()
    {
        return [
            ['id1', 'type1', 'url1', 'content1'],
            ['id2', 'type2', 'url1', 'content2'],
        ];
    }

    /**
     * @dataProvider provider1
     */
    public function testArgumentGetters($id, $type, $url, $content)
    {
        $asset = new Asset($id, $type, $url, $content);

        $this->assertEquals($id, $asset->getId());
        $this->assertEquals($type, $asset->getType());
        $this->assertEquals($url, $asset->getUrl());
        $this->assertEquals($content, $asset->getContent());
    }
}
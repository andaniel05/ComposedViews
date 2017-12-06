<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\StyleAsset;
use Andaniel05\ComposedViews\Asset\UriInterface;

class StyleAssetTest extends TestCase
{
    use CommonTrait, CommonStyleImportTrait;

    public function newInstance(array $args = [])
    {
        $defaults = [
            'id'     => uniqid(),
            'uri'    => '',
            'deps'   => '',
            'groups' => '',
        ];

        $args = array_merge($defaults, $args);
        extract($args);

        return new StyleAsset($id, $uri, $deps, $groups);
    }

    public function testHasStylesGroup()
    {
        $this->assertTrue($this->asset->hasGroup('styles'));
    }

    public function testHasRelAttributeEqualToStylesheet()
    {
        $this->assertEquals('stylesheet', $this->asset->getAttribute('rel'));
    }
}

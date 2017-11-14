<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\{StyleAsset, UriInterface};

class StyleAssetTest extends TestCase
{
    use CommonTrait, CommonStyleImportTrait;

    public function newInstance(array $args = [])
    {
        $defaults = [
            'id'     => uniqid(),
            'uri'    => uniqid(),
            'deps'   => uniqid(),
            'groups' => uniqid(),
        ];

        $args = array_merge($defaults, $args);
        extract($args);

        return new StyleAsset($id, $uri, $deps, $groups);
    }

    public function testHasStylesGroup()
    {
        $this->assertTrue($this->asset->hasGroup('styles'));
    }
}

<?php

namespace Andaniel05\ComposedViews\Tests\Asset2;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset2\{ImportAsset, UriInterface};

class ImportAssetTest extends TestCase
{
    use CommonTrait, StyleImportCommonTrait;

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

        return new ImportAsset($id, $uri, $deps, $groups);
    }

    public function testHasRelImportAttribute()
    {
        $this->assertEquals('import', $this->asset->getAttribute('rel'));
    }

    public function testHasImportsGroup()
    {
        $this->assertTrue($this->asset->hasGroup('imports'));
    }
}

<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\{ImportAsset, UriInterface};

class ImportAssetTest extends TestCase
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

<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\ImportAsset;
use Andaniel05\ComposedViews\Asset\UriInterface;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class ImportAssetTest extends TestCase
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

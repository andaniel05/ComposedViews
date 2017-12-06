<?php

namespace Andaniel05\ComposedViews\Tests\Asset;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\ContentStyleAsset;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class ContentStyleAssetTest extends TestCase
{
    use CommonTrait;

    public function newInstance(array $args = [])
    {
        $defaults = [
            'id'      => uniqid(),
            'content' => [],
            'deps'    => '',
            'groups'  => '',
        ];

        $args = array_merge($defaults, $args);
        extract($args);

        return new ContentStyleAsset($id, $content, $deps, $groups);
    }

    public function testTagIsEqualToStyle()
    {
        $this->assertEquals('style', $this->asset->getTag());
    }

    public function testHasStylesGroup()
    {
        $this->assertTrue($this->asset->hasGroup('styles'));
    }

    public function testHasContentGroup()
    {
        $this->assertTrue($this->asset->hasGroup('content'));
    }

    public function testContentIsEqualToContentArgument()
    {
        $content = uniqid();
        $asset = $this->newInstance(['content' => $content]);

        $this->assertEquals([$content], $asset->getContent());
    }
}

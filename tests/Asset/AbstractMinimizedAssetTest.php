<?php

namespace Andaniel05\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Asset\AbstractMinimizedAsset;

class AbstractMinimizedAssetTest extends TestCase
{
    public function setUp()
    {
        $id = uniqid();

        $this->asset = $this->getMockForAbstractClass(AbstractMinimizedAsset::class, [$id]);
    }

    public function testGetMinimizedContent_ReturnValueOfTheContentWhenHisMinimizedContentIsNull()
    {
        $content = uniqid();
        $this->asset->setContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
    }

    public function testGetMinimizedContent_ReturnInsertedValueBySetMinimizedContent()
    {
        $content = uniqid();
        $this->asset->setMinimizedContent($content);

        $this->assertEquals($content, $this->asset->getMinimizedContent());
    }

    public function testIsMinimized_ReturnTrueByDefault()
    {
        $this->assertTrue($this->asset->isMinimized());
    }

    public function testIsMinimized_ReturnInsertedValueBySetMinimized()
    {
        $this->asset->setMinimized(false);

        $this->assertFalse($this->asset->isMinimized());
    }
}
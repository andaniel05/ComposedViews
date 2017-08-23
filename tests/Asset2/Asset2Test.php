<?php

namespace PlatformPHP\ComposedViews\Tests;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Asset2\Asset;

class Asset2Test extends TestCase
{
    public function testGetId_ReturnTheIdArgument()
    {
        $id = uniqid();

        $asset = new Asset($id);

        $this->assertEquals($id, $asset->getId());
    }
}
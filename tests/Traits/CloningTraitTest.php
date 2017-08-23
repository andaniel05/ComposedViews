<?php

namespace PlatformPHP\ComposedViews\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PlatformPHP\ComposedViews\Traits\CloningTrait;

class CloningTraitTest extends TestCase
{
    public function testCloneMethodReturnNewInstance()
    {
        $instance = $this->getMockBuilder(CloningTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait();

        $newInstance = $instance->clone();

        $this->assertNotEquals((array) $instance, (array) $newInstance);
    }
}
<?php

namespace Andaniel05\ComposedViews\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Andaniel05\ComposedViews\Traits\CloningTrait;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
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
